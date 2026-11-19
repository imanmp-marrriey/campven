<?php

namespace Tests\Feature;

use App\Models\Announcement;
use App\Models\Category;
use App\Models\Event;
use App\Models\Participant;
use App\Models\User;
use App\Support\QrCodeSvg;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class CampvenAuditTest extends TestCase
{
    use RefreshDatabase;

    private function createCategory(string $nama = 'Seminar'): Category
    {
        return Category::create([
            'nama' => $nama,
            'icon' => 'fa-graduation-cap',
            'color' => '#4F46E5',
            'description' => 'Kategori ' . $nama,
        ]);
    }

    private function createUser(string $role = 'mahasiswa', array $overrides = []): User
    {
        static $idx = 1;
        $n = $idx++;
        return User::create(array_merge([
            'nama' => ucfirst($role) . ' ' . $n,
            'email' => $role . $n . '@campven.test',
            'password' => Hash::make('password'),
            'role' => $role,
            'no_telp' => '0812345678' . $n,
        ], $overrides));
    }

    private function createEvent(User $creator, array $overrides = []): Event
    {
        return Event::create(array_merge([
            'nama_event' => 'Seminar Nasional AI',
            'kategori' => 'Seminar',
            'tanggal' => now()->addDays(7)->format('Y-m-d'),
            'waktu_mulai' => '09:00',
            'waktu_selesai' => '12:00',
            'lokasi' => 'Auditorium Utama',
            'deskripsi' => 'Deskripsi event resmi.',
            'kuota' => 50,
            'harga' => 0,
            'skpi_poin' => 15,
            'created_by' => $creator->id,
        ], $overrides));
    }

    public function test_public_pages_and_announcements_render_with_real_columns(): void
    {
        $admin = $this->createUser('admin');
        $org = $this->createUser('penyelenggara');
        $this->createCategory('Seminar');
        $event = $this->createEvent($org);

        Announcement::create([
            'title' => 'Pengumuman Penting Kampus',
            'content' => 'Harap membawa KTM saat menghadiri acara.',
            'created_by' => $admin->id,
        ]);

        $this->get(route('home'))
            ->assertStatus(200)
            ->assertSee('Pengumuman Penting Kampus')
            ->assertSee('Harap membawa KTM saat menghadiri acara.')
            ->assertSee($event->nama_event)
            ->assertSee('+15 SKPI');

        $this->get(route('event.detail', $event->id))
            ->assertStatus(200)
            ->assertSee($event->nama_event)
            ->assertSee($org->nama)
            ->assertDontSee('Dr. Rayna Prasetya')
            ->assertDontSee('Parkir Gratis');

        $this->get(route('login'))->assertStatus(200);
        $this->get(route('register'))->assertStatus(200)->assertDontSee('name="role"', false);
    }

    public function test_public_registration_locks_role_to_mahasiswa(): void
    {
        $response = $this->post('/register', [
            'nama' => 'Pendaftar Coba Role',
            'email' => 'baru@campven.test',
            'password' => 'secret123',
            'password_confirmation' => 'secret123',
            'no_telp' => '08123456789',
            'role' => 'penyelenggara',
        ]);

        $response->assertRedirect(route('login'));
        $user = User::where('email', 'baru@campven.test')->firstOrFail();
        $this->assertSame('mahasiswa', $user->role);

        $response2 = $this->post('/register', [
            'nama' => 'Pendaftar Admin Role',
            'email' => 'baru2@campven.test',
            'password' => 'secret123',
            'password_confirmation' => 'secret123',
            'role' => 'admin',
        ]);
        $response2->assertRedirect(route('login'));
        $this->assertSame('mahasiswa', User::where('email', 'baru2@campven.test')->firstOrFail()->role);
    }

    public function test_role_middleware_enforcement_and_login_rate_limiting(): void
    {
        $mahasiswa = $this->createUser('mahasiswa');
        $org = $this->createUser('penyelenggara');

        $this->actingAs($mahasiswa)->get(route('admin.dashboard'))->assertStatus(403);
        $this->actingAs($mahasiswa)->get(route('organizer.dashboard'))->assertStatus(403);
        $this->actingAs($org)->get(route('admin.dashboard'))->assertStatus(403);

        auth()->logout();

        for ($i = 0; $i < 5; $i++) {
            $this->post('/login', [
                'email' => $mahasiswa->email,
                'password' => 'salah-password',
            ])->assertStatus(302);
        }

        $this->post('/login', [
            'email' => $mahasiswa->email,
            'password' => 'salah-password',
        ])->assertStatus(429);
    }

    public function test_mahasiswa_registration_constraints_cancel_and_reregister(): void
    {
        $mahasiswa = $this->createUser('mahasiswa');
        $mahasiswa2 = $this->createUser('mahasiswa');
        $org = $this->createUser('penyelenggara');
        $admin = $this->createUser('admin');
        $this->createCategory();

        $event = $this->createEvent($org, ['kuota' => 1]);
        $pastEvent = $this->createEvent($org, [
            'nama_event' => 'Acara Lampau',
            'tanggal' => now()->subDays(2)->format('Y-m-d'),
        ]);

        // Admin dan Penyelenggara tidak boleh mendaftar sebagai peserta
        $this->actingAs($admin)->post(route('event.register', $event->id))
            ->assertRedirect(route('event.detail', $event->id))
            ->assertSessionHas('error');
        $this->assertDatabaseCount('participants', 0);

        $this->actingAs($org)->post(route('event.register', $event->id))
            ->assertRedirect(route('event.detail', $event->id))
            ->assertSessionHas('error');
        $this->assertDatabaseCount('participants', 0);

        // Mahasiswa tidak boleh mendaftar ke event yang sudah lewat
        $this->actingAs($mahasiswa)->post(route('event.register', $pastEvent->id))
            ->assertRedirect(route('event.detail', $pastEvent->id))
            ->assertSessionHas('error');
        $this->assertDatabaseCount('participants', 0);

        // Mahasiswa 1 daftar ke event GRATIS mendatang -> langsung auto-confirmed (Hybrid Flow)
        $this->actingAs($mahasiswa)->post(route('event.register', $event->id))
            ->assertRedirect(route('event.detail', $event->id))
            ->assertSessionHas('success');
        $this->assertDatabaseHas('participants', [
            'event_id' => $event->id,
            'user_id' => $mahasiswa->id,
            'status' => 'confirmed',
        ]);

        // Daftar ganda oleh mahasiswa 1 ditolak
        $this->actingAs($mahasiswa)->post(route('event.register', $event->id))
            ->assertRedirect(route('event.detail', $event->id))
            ->assertSessionHas('error');

        // Kuota penuh (1/1): mahasiswa 2 ditolak
        $this->actingAs($mahasiswa2)->post(route('event.register', $event->id))
            ->assertRedirect(route('event.detail', $event->id))
            ->assertSessionHas('error');

        // Mahasiswa 1 membatalkan pendaftaran -> kuota bebas lagi -> mahasiswa 1 bisa daftar ulang (auto-confirmed)
        $this->actingAs($mahasiswa)->post(route('my.events.cancel', $event->id))
            ->assertRedirect(route('my.events'))
            ->assertSessionHas('success');
        $this->assertDatabaseHas('participants', [
            'event_id' => $event->id,
            'user_id' => $mahasiswa->id,
            'status' => 'canceled',
        ]);
        $this->assertFalse($event->fresh()->isFull());

        $this->actingAs($mahasiswa)->post(route('event.register', $event->id))
            ->assertRedirect(route('event.detail', $event->id))
            ->assertSessionHas('success');
        $this->assertDatabaseHas('participants', [
            'event_id' => $event->id,
            'user_id' => $mahasiswa->id,
            'status' => 'confirmed',
        ]);

        // Event BERBAYAR (harga > 0) -> status awal harus 'registered' menunggu konfirmasi penyelenggara
        $paidEvent = $this->createEvent($org, [
            'nama_event' => 'Workshop Berbayar Hybrid',
            'harga' => 25000,
            'kuota' => 20,
        ]);
        $this->actingAs($mahasiswa2)->post(route('event.register', $paidEvent->id))
            ->assertRedirect(route('event.detail', $paidEvent->id))
            ->assertSessionHas('success');
        $this->assertDatabaseHas('participants', [
            'event_id' => $paidEvent->id,
            'user_id' => $mahasiswa2->id,
            'status' => 'registered',
        ]);

        // Mahasiswa mengunggah bukti pembayaran -> tersimpan di disk public & terlihat di halaman panitia
        Storage::fake('public');
        $proofImg = UploadedFile::fake()->image('struk_transfer.jpg', 400, 600);
        $this->actingAs($mahasiswa2)->post(route('my.events.paymentProof', $paidEvent->id), [
            'bukti_bayar' => $proofImg,
        ])->assertRedirect(route('my.events'))->assertSessionHas('success');

        $paidParticipant = Participant::where('event_id', $paidEvent->id)->where('user_id', $mahasiswa2->id)->firstOrFail();
        $this->assertNotNull($paidParticipant->bukti_bayar);
        Storage::disk('public')->assertExists($paidParticipant->bukti_bayar);

        $this->actingAs($org)->get(route('organizer.event.participants', $paidEvent->id))
            ->assertStatus(200)
            ->assertSee('Lihat Bukti');
    }

    public function test_my_events_qr_svg_and_certificate_access_control(): void
    {
        $mahasiswa = $this->createUser('mahasiswa');
        $otherMahasiswa = $this->createUser('mahasiswa');
        $org = $this->createUser('penyelenggara');
        $this->createCategory();
        $event = $this->createEvent($org, ['skpi_poin' => 25]);

        $participant = Participant::create([
            'event_id' => $event->id,
            'user_id' => $mahasiswa->id,
            'status' => 'confirmed',
        ]);

        $svg = QrCodeSvg::generate($participant->ticketCode(), 96);
        $this->assertStringContainsString('<svg', $svg);

        $this->actingAs($mahasiswa)->get(route('my.events'))
            ->assertStatus(200)
            ->assertSee($participant->ticketCode())
            ->assertSee('<svg', false);

        // Status confirmed belum boleh cetak sertifikat
        $this->actingAs($mahasiswa)->get(route('my.events.certificate', $event->id))
            ->assertStatus(403);

        $participant->update(['status' => 'attended']);

        // Mahasiswa lain ditolak
        $this->actingAs($otherMahasiswa)->get(route('my.events.certificate', $event->id))
            ->assertStatus(404);

        // Mahasiswa pemilik yang sudah attended berhasil cetak
        $this->actingAs($mahasiswa)->get(route('my.events.certificate', $event->id))
            ->assertStatus(200)
            ->assertSee($mahasiswa->nama)
            ->assertSee($event->nama_event)
            ->assertSee('+25 Poin SKPI');
    }

    public function test_organizer_anti_idor_and_exact_checkin_logic(): void
    {
        $orgA = $this->createUser('penyelenggara');
        $orgB = $this->createUser('penyelenggara');
        $mhs1 = $this->createUser('mahasiswa', ['nama' => 'Budi Santoso', 'email' => 'budi1@campven.test']);
        $mhs11 = $this->createUser('mahasiswa', ['nama' => 'Budi Santoso 11', 'email' => 'budi11@campven.test']);
        $mhsCanceled = $this->createUser('mahasiswa', ['nama' => 'Siti Batal', 'email' => 'siti@campven.test']);
        $this->createCategory();

        $eventA = $this->createEvent($orgA, ['nama_event' => 'Acara A']);
        $eventB = $this->createEvent($orgB, ['nama_event' => 'Acara B']);

        $pB = Participant::create(['event_id' => $eventB->id, 'user_id' => $mhs1->id, 'status' => 'registered']);

        // Anti-IDOR: Org A tidak boleh akses atau ubah event/peserta milik Org B
        $this->actingAs($orgA)->get(route('organizer.event.participants', $eventB->id))->assertStatus(404);
        $this->actingAs($orgA)->get(route('organizer.event.edit', $eventB->id))->assertStatus(404);
        $this->actingAs($orgA)->put(route('organizer.event.update', $eventB->id), [
            'nama_event' => 'Hack',
            'kategori' => 'Seminar',
            'tanggal' => now()->addDays(5)->format('Y-m-d'),
            'lokasi' => 'Online',
        ])->assertStatus(404);
        $this->actingAs($orgA)->delete(route('organizer.event.delete', $eventB->id))->assertStatus(404);
        $this->actingAs($orgA)->get(route('organizer.exportCsv', $eventB->id))->assertStatus(404);
        $this->actingAs($orgA)->post(route('organizer.participant.confirm', $pB->id))->assertStatus(404);
        $this->actingAs($orgA)->post(route('organizer.participant.attend', $pB->id))->assertStatus(404);
        $this->actingAs($orgA)->post(route('organizer.participant.cancel', $pB->id))->assertStatus(404);
        $this->actingAs($orgA)->post(route('organizer.quickCheckin'), [
            'event_id' => $eventB->id,
            'query' => $pB->ticketCode(),
        ])->assertStatus(404);
        $this->assertSame('registered', $pB->fresh()->status);

        // Exact Check-in pada Event A
        $pA1 = Participant::create(['event_id' => $eventA->id, 'user_id' => $mhs1->id, 'status' => 'registered']);
        $pA11 = Participant::create(['event_id' => $eventA->id, 'user_id' => $mhs11->id, 'status' => 'confirmed']);
        $pACanceled = Participant::create(['event_id' => $eventA->id, 'user_id' => $mhsCanceled->id, 'status' => 'canceled']);

        // Partial nama / angka yang tidak eksak tidak boleh salah presensi orang lain
        $this->actingAs($orgA)->post(route('organizer.quickCheckin'), [
            'event_id' => $eventA->id,
            'query' => 'Budi',
        ])->assertSessionHas('error');
        $this->assertSame('registered', $pA1->fresh()->status);
        $this->assertSame('confirmed', $pA11->fresh()->status);

        // Tiket Event B tidak valid untuk Event A
        $this->actingAs($orgA)->post(route('organizer.quickCheckin'), [
            'event_id' => $eventA->id,
            'query' => $pB->ticketCode(),
        ])->assertSessionHas('error');

        // Peserta canceled ditolak oleh quickCheckin, confirm, maupun attend
        $this->actingAs($orgA)->post(route('organizer.quickCheckin'), [
            'event_id' => $eventA->id,
            'query' => $pACanceled->ticketCode(),
        ])->assertSessionHas('error');
        $this->actingAs($orgA)->post(route('organizer.participant.confirm', $pACanceled->id))->assertSessionHas('error');
        $this->actingAs($orgA)->post(route('organizer.participant.attend', $pACanceled->id))->assertSessionHas('error');
        $this->assertSame('canceled', $pACanceled->fresh()->status);

        // Presensi eksak dengan kode tiket & email eksak
        $this->actingAs($orgA)->post(route('organizer.quickCheckin'), [
            'event_id' => $eventA->id,
            'query' => $pA1->ticketCode(),
        ])->assertSessionHas('success');
        $this->assertSame('attended', $pA1->fresh()->status);

        $this->actingAs($orgA)->post(route('organizer.quickCheckin'), [
            'event_id' => $eventA->id,
            'query' => 'budi11@campven.test',
        ])->assertSessionHas('success');
        $this->assertSame('attended', $pA11->fresh()->status);

        // Event yang hanya punya peserta canceled boleh dihapus
        $eventOnlyCanceled = $this->createEvent($orgA, ['nama_event' => 'Event Batal Saja']);
        Participant::create(['event_id' => $eventOnlyCanceled->id, 'user_id' => $mhsCanceled->id, 'status' => 'canceled']);
        $this->actingAs($orgA)->delete(route('organizer.event.delete', $eventOnlyCanceled->id))
            ->assertRedirect(route('organizer.dashboard'))
            ->assertSessionHas('success');
        $this->assertDatabaseMissing('events', ['id' => $eventOnlyCanceled->id]);

        // Export CSV, Excel (.xls), dan Word (.doc) Event A berhasil + Anti-IDOR untuk Event B
        $this->actingAs($orgA)->get(route('organizer.exportCsv', $eventA->id))
            ->assertStatus(200)
            ->assertHeader('Content-Type', 'text/csv; charset=utf-8');

        $this->actingAs($orgA)->get(route('organizer.exportReport', ['format' => 'excel', 'event_id' => $eventA->id]))
            ->assertStatus(200)
            ->assertHeader('Content-Type', 'application/vnd.ms-excel; charset=utf-8')
            ->assertSee('<table class="data-table">', false)
            ->assertSee($mhs1->nama);

        $this->actingAs($orgA)->get(route('organizer.exportReport', ['format' => 'word', 'event_id' => $eventA->id]))
            ->assertStatus(200)
            ->assertHeader('Content-Type', 'application/msword; charset=utf-8')
            ->assertSee('Penanggung Jawab Kegiatan');

        $this->actingAs($orgA)->get(route('organizer.exportReport', ['format' => 'excel', 'event_id' => $eventB->id]))
            ->assertStatus(404);
    }

    public function test_admin_self_protection_and_cruds(): void
    {
        $admin = $this->createUser('admin');
        $mahasiswa = $this->createUser('mahasiswa');
        $this->createCategory('Seminar');

        // Admin tidak bisa menurunkan role atau menghapus dirinya sendiri
        $this->actingAs($admin)->put(route('admin.user.update', $admin->id), [
            'nama' => $admin->nama,
            'email' => $admin->email,
            'role' => 'mahasiswa',
        ])->assertRedirect(route('admin.users'))->assertSessionHas('error');
        $this->assertSame('admin', $admin->fresh()->role);

        $this->actingAs($admin)->delete(route('admin.user.delete', $admin->id))
            ->assertRedirect(route('admin.users'))
            ->assertSessionHas('error');
        $this->assertDatabaseHas('users', ['id' => $admin->id]);

        // Admin membuat & mengedit user ke penyelenggara
        $this->actingAs($admin)->post(route('admin.user.store'), [
            'nama' => 'UKM Musik',
            'email' => 'ukm.musik@campven.test',
            'password' => 'password123',
            'role' => 'penyelenggara',
        ])->assertRedirect(route('admin.users'))->assertSessionHas('success');
        $org = User::where('email', 'ukm.musik@campven.test')->firstOrFail();
        $this->assertSame('penyelenggara', $org->role);

        // Admin CRUD pengumuman
        $this->actingAs($admin)->post(route('admin.announcement.store'), [
            'title' => 'Info Beasiswa & Lomba',
            'content' => 'Pendaftaran dibuka hingga akhir bulan.',
        ])->assertRedirect(route('admin.announcements'))->assertSessionHas('success');
        $ann = Announcement::firstOrFail();

        $this->actingAs($admin)->put(route('admin.announcement.update', $ann->id), [
            'title' => 'Info Lomba Diperpanjang',
            'content' => 'Diperpanjang 1 minggu.',
        ])->assertRedirect(route('admin.announcements'))->assertSessionHas('success');
        $this->assertSame('Info Lomba Diperpanjang', $ann->fresh()->title);

        $this->actingAs($admin)->delete(route('admin.announcement.delete', $ann->id))
            ->assertRedirect(route('admin.announcements'))
            ->assertSessionHas('success');
        $this->assertDatabaseCount('announcements', 0);

        // Admin tambah event dengan harga & skpi_poin serta lihat daftar peserta
        $this->actingAs($admin)->post(route('admin.event.store'), [
            'nama_event' => 'Konser Kampus',
            'kategori' => 'Seminar',
            'tanggal' => now()->addDays(10)->format('Y-m-d'),
            'lokasi' => 'Lapangan Utama',
            'kuota' => 200,
            'harga' => 25000,
            'skpi_poin' => 20,
            'created_by' => $org->id,
        ])->assertRedirect(route('admin.dashboard'))->assertSessionHas('success');

        $ev = Event::where('nama_event', 'Konser Kampus')->firstOrFail();
        $this->assertSame(25000, (int) $ev->harga);
        $this->assertSame(20, (int) $ev->skpi_poin);
        $this->assertSame($org->id, (int) $ev->created_by);

        Participant::create(['event_id' => $ev->id, 'user_id' => $mahasiswa->id, 'status' => 'confirmed']);
        $this->actingAs($admin)->get(route('admin.event.participants', $ev->id))
            ->assertStatus(200)
            ->assertSee($mahasiswa->nama);

        $this->actingAs($admin)->get(route('admin.event.exportCsv', $ev->id))
            ->assertStatus(200)
            ->assertHeader('Content-Type', 'text/csv; charset=utf-8');

        // Admin CRUD kategori
        $this->actingAs($admin)->post(route('admin.category.store'), [
            'nama' => 'Bakti Sosial',
            'icon' => 'fa-hands-helping',
            'color' => '#10B981',
            'description' => 'Kegiatan sosial',
        ])->assertRedirect(route('admin.categories'))->assertSessionHas('success');
        $cat = Category::where('nama', 'Bakti Sosial')->firstOrFail();

        $this->actingAs($admin)->put(route('admin.category.update', $cat->id), [
            'nama' => 'Pengabdian Masyarakat',
            'icon' => 'fa-hands-helping',
            'color' => '#10B981',
            'description' => 'Update deskripsi',
        ])->assertRedirect(route('admin.categories'))->assertSessionHas('success');
        $this->assertSame('Pengabdian Masyarakat', $cat->fresh()->nama);

        $this->actingAs($admin)->delete(route('admin.category.delete', $cat->id))
            ->assertRedirect(route('admin.categories'))
            ->assertSessionHas('success');
        $this->assertDatabaseMissing('categories', ['id' => $cat->id]);
    }

    public function test_ticket_verify_page_url_checkin_profile_update_and_logout(): void
    {
        $org = $this->createUser('penyelenggara');
        $otherOrg = $this->createUser('penyelenggara');
        $mhs = $this->createUser('mahasiswa');
        $this->createCategory('Seminar');
        $event = $this->createEvent($org);

        $participant = Participant::create([
            'event_id' => $event->id,
            'user_id' => $mhs->id,
            'status' => 'confirmed',
        ]);

        // Publik bisa membuka halaman verifikasi tiket (misalnya hasil scan QR)
        $this->get(route('ticket.verify', $participant->ticketCode()))
            ->assertStatus(200)
            ->assertSee($participant->ticketCode())
            ->assertSee($mhs->nama)
            ->assertSee($event->nama_event)
            ->assertDontSee('Verifikasi & Catat Hadir Sekarang');

        // Tiket palsu menampilkan halaman Tiket Tidak Ditemukan
        $this->get(route('ticket.verify', 'CV-999-999'))
            ->assertStatus(200)
            ->assertSee('Tiket Tidak Ditemukan');

        // Penyelenggara lain tidak melihat tombol catat hadir
        $this->actingAs($otherOrg)->get(route('ticket.verify', $participant->ticketCode()))
            ->assertStatus(200)
            ->assertDontSee('Verifikasi & Catat Hadir Sekarang');

        // Penyelenggara pemilik melihat tombol catat hadir & bisa check-in via URL QR
        $this->actingAs($org)->get(route('ticket.verify', $participant->ticketCode()))
            ->assertStatus(200)
            ->assertSee('Verifikasi &amp; Catat Hadir Sekarang', false);

        $this->actingAs($org)->post(route('organizer.quickCheckin'), [
            'event_id' => $event->id,
            'query' => route('ticket.verify', $participant->ticketCode()),
        ])->assertSessionHas('success');
        $this->assertSame('attended', $participant->fresh()->status);

        // Mahasiswa update profil & logout
        $this->actingAs($mhs)->post(route('profile.update'), [
            'nama' => 'Nama Mahasiswa Baru',
            'no_telp' => '089999999999',
            'bio' => 'Bio diperbarui',
        ])->assertRedirect(route('profile'))->assertSessionHas('success');
        $this->assertSame('Nama Mahasiswa Baru', $mhs->fresh()->nama);

        $this->actingAs($mhs)->post(route('logout'))
            ->assertRedirect(route('home'));
        $this->assertGuest();
    }

    public function test_home_search_filters_sorting_and_organizer_event_crud(): void
    {
        $org = $this->createUser('penyelenggara');
        $this->createCategory('Seminar');
        $this->createCategory('Workshop');

        // Organizer tambah & edit event
        $this->actingAs($org)->post(route('organizer.event.store'), [
            'nama_event' => 'Workshop DevOps Kampus',
            'kategori' => 'Workshop',
            'tanggal' => now()->addDays(15)->format('Y-m-d'),
            'waktu_mulai' => '10:00',
            'waktu_selesai' => '14:00',
            'lokasi' => 'Lab Cloud Lt. 2',
            'kuota' => 35,
            'harga' => 10000,
            'skpi_poin' => 12,
            'deskripsi' => 'Belajar Docker dan CI/CD.',
        ])->assertRedirect(route('organizer.dashboard'))->assertSessionHas('success');

        $ev = Event::where('nama_event', 'Workshop DevOps Kampus')->firstOrFail();
        $this->assertSame(12, (int) $ev->skpi_poin);

        $this->actingAs($org)->put(route('organizer.event.update', $ev->id), [
            'nama_event' => 'Workshop DevOps Kampus v2',
            'kategori' => 'Workshop',
            'tanggal' => now()->addDays(16)->format('Y-m-d'),
            'waktu_mulai' => '10:00',
            'waktu_selesai' => '15:00',
            'lokasi' => 'Lab Cloud Lt. 2',
            'kuota' => 40,
            'harga' => 15000,
            'skpi_poin' => 15,
        ])->assertRedirect(route('organizer.dashboard'))->assertSessionHas('success');
        $this->assertSame('Workshop DevOps Kampus v2', $ev->fresh()->nama_event);

        // Uji filter pencarian, kategori, lokasi, status, dan sortir di beranda
        $pastEv = $this->createEvent($org, [
            'nama_event' => 'Arsip Seminar Lama',
            'kategori' => 'Seminar',
            'tanggal' => now()->subDays(10)->format('Y-m-d'),
        ]);

        // Default beranda hanya menampilkan agenda mendatang
        $this->get(route('home'))
            ->assertStatus(200)
            ->assertSee('Workshop DevOps Kampus v2')
            ->assertDontSee('Arsip Seminar Lama');

        // Status selesai menampilkan arsip
        $this->get(route('home', ['status' => 'selesai']))
            ->assertStatus(200)
            ->assertSee('Arsip Seminar Lama');

        $this->get(route('home', ['search' => 'DevOps', 'kategori' => 'Workshop', 'lokasi_filter' => 'Lab', 'sort' => 'kuota']))
            ->assertStatus(200)
            ->assertSee('Workshop DevOps Kampus v2');

        // Uji upload foto profil (tersimpan ke disk public & menghapus foto lama saat diganti)
        Storage::fake('public');
        $mhs = $this->createUser('mahasiswa');
        $file1 = UploadedFile::fake()->image('avatar1.jpg', 200, 200);
        $this->actingAs($mhs)->post(route('profile.update'), [
            'nama' => $mhs->nama,
            'foto' => $file1,
        ])->assertRedirect(route('profile'))->assertSessionHas('success');

        $firstPhoto = $mhs->fresh()->foto;
        $this->assertNotNull($firstPhoto);
        Storage::disk('public')->assertExists($firstPhoto);

        $file2 = UploadedFile::fake()->image('avatar2.png', 200, 200);
        $this->actingAs($mhs)->post(route('profile.update'), [
            'nama' => $mhs->nama,
            'foto' => $file2,
        ])->assertRedirect(route('profile'))->assertSessionHas('success');

        $secondPhoto = $mhs->fresh()->foto;
        $this->assertNotSame($firstPhoto, $secondPhoto);
        Storage::disk('public')->assertMissing($firstPhoto);
        Storage::disk('public')->assertExists($secondPhoto);
    }
}

