<?php

namespace Database\Seeders;

use App\Models\Announcement;
use App\Models\Category;
use App\Models\Event;
use App\Models\Participant;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DemoFlowSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Users (Non-destruktif & Idempotent)
        $password = Hash::make('password');

        $admin = User::updateOrCreate(
            ['email' => 'admin@campven.com'],
            [
                'nama' => 'Admin Utama',
                'password' => $password,
                'role' => 'admin',
                'no_telp' => '081100000001',
                'bio' => 'Administrator utama pengelola sistem informasi event kampus Campven.',
            ]
        );

        $bem = User::updateOrCreate(
            ['email' => 'bem@campven.com'],
            [
                'nama' => 'BEM Polindra',
                'password' => $password,
                'role' => 'penyelenggara',
                'no_telp' => '081200000001',
                'bio' => 'Badan Eksekutif Mahasiswa penyelenggara seminar, workshop, dan kegiatan kemahasiswaan.',
            ]
        );

        $himatif = User::updateOrCreate(
            ['email' => 'himti@campven.com'],
            [
                'nama' => 'HIMTI',
                'password' => $password,
                'role' => 'penyelenggara',
                'no_telp' => '081200000002',
                'bio' => 'Himpunan Mahasiswa Teknik Informatika.',
            ]
        );

        $mhs1 = User::updateOrCreate(
            ['email' => 'mahasiswa1@campven.com'],
            [
                'nama' => 'Raka Pratama',
                'password' => $password,
                'role' => 'mahasiswa',
                'no_telp' => '081300000001',
                'bio' => 'Mahasiswa aktif Teknik Informatika.',
            ]
        );

        $mhs2 = User::updateOrCreate(
            ['email' => 'mahasiswa2@campven.com'],
            [
                'nama' => 'Siti Aulia',
                'password' => $password,
                'role' => 'mahasiswa',
                'no_telp' => '081300000002',
                'bio' => 'Mahasiswa aktif Sistem Informasi.',
            ]
        );

        $mhs3 = User::updateOrCreate(
            ['email' => 'mahasiswa3@campven.com'],
            [
                'nama' => 'Budi Santoso',
                'password' => $password,
                'role' => 'mahasiswa',
                'no_telp' => '081300000003',
                'bio' => 'Mahasiswa aktif Manajemen Informatika.',
            ]
        );

        // 2. Categories
        $categories = [
            [
                'nama' => 'Seminar & Webinar',
                'icon' => 'fa-graduation-cap',
                'color' => '#4F46E5',
                'description' => 'Seminar tatap muka dan webinar daring akademik maupun pengembangan karier.',
            ],
            [
                'nama' => 'Workshop & Pelatihan',
                'icon' => 'fa-laptop-code',
                'color' => '#0EA5E9',
                'description' => 'Pelatihan praktik langsung untuk meningkatkan keterampilan teknis mahasiswa.',
            ],
            [
                'nama' => 'Lomba & Kompetisi',
                'icon' => 'fa-trophy',
                'color' => '#F59E0B',
                'description' => 'Kompetisi inovasi, hackathon, dan perlombaan tingkat kampus maupun nasional.',
            ],
            [
                'nama' => 'Kegiatan UKM',
                'icon' => 'fa-users',
                'color' => '#10B981',
                'description' => 'Agenda rutin, temu karya, dan rekrutmen terbuka UKM dan organisasi mahasiswa.',
            ],
        ];

        foreach ($categories as $cat) {
            Category::firstOrCreate(['nama' => $cat['nama']], $cat);
        }

        // 3. Announcements (3 pengumuman realistis menggunakan kolom title & content)
        $announcements = [
            [
                'title' => 'Ketentuan Presensi Tiket & Klaim Poin SKPI Semester Ini',
                'content' => 'Mahasiswa wajib menunjukkan kode tiket (CV-...) atau QR Code di halaman Tiket Saya kepada panitia di lokasi acara. E-Sertifikat dan poin SKPI hanya dapat dicetak setelah status kehadiran berubah menjadi Sudah Hadir.',
            ],
            [
                'title' => 'Pendaftaran Kegiatan Organisasi & UKM Periode Baru Dibuka',
                'content' => 'Seluruh mahasiswa aktif dapat mulai mendaftar pada berbagai seminar, workshop, dan agenda UKM melalui portal Campven. Pastikan kuota masih tersedia sebelum tanggal pelaksanaan.',
            ],
            [
                'title' => 'Panduan Pengajuan Akun Penyelenggara Organisasi Kampus',
                'content' => 'Pengurus BEM, Himpunan, dan UKM yang membutuhkan akun Penyelenggara dapat menghubungi Administrator Kampus melalui menu Pusat Bantuan untuk aktivasi hak akses publikasi acara.',
            ],
        ];

        foreach ($announcements as $ann) {
            Announcement::firstOrCreate(
                ['title' => $ann['title']],
                [
                    'content' => $ann['content'],
                    'created_by' => $admin->id,
                ]
            );
        }

        // 4. Events (5 skenario lengkap dengan poster gambar nyata)
        $eventA = Event::updateOrCreate(
            ['nama_event' => 'Seminar Nasional AI & Masa Depan Karier Digital', 'created_by' => $bem->id],
            [
                'kategori' => 'Seminar & Webinar',
                'tanggal' => Carbon::today()->addDays(10)->format('Y-m-d'),
                'waktu_mulai' => '09:00',
                'waktu_selesai' => '12:00',
                'lokasi' => 'Auditorium Utama Kampus',
                'deskripsi' => 'Seminar nasional membahas penerapan kecerdasan buatan di dunia industri serta persiapan karier digital bagi mahasiswa lintas jurusan.',
                'poster' => 'posters/1783481548_gpt-image-2_A_professional_seminar_poster_with_a_futuristic_AI_theme_featuring_glowing_neura-01.jpg',
                'kuota' => 50,
                'harga' => 0,
                'skpi_poin' => 15,
            ]
        );

        $eventB = Event::updateOrCreate(
            ['nama_event' => 'Workshop Intensive Fullstack Laravel 12', 'created_by' => $bem->id],
            [
                'kategori' => 'Workshop & Pelatihan',
                'tanggal' => Carbon::today()->addDays(14)->format('Y-m-d'),
                'waktu_mulai' => '13:00',
                'waktu_selesai' => '17:00',
                'lokasi' => 'Lab Komputer Terpadu Lt. 3',
                'deskripsi' => 'Pelatihan intensif membangun aplikasi web modern menggunakan Laravel 12, Blade, dan Tailwind CSS dengan studi kasus nyata.',
                'poster' => 'posters/1783481488_gpt-image-2_Modern_data_science_bootcamp_poster_data_visualization_with_charts_and_graphs_Py-0.jpg',
                'kuota' => 2,
                'harga' => 25000,
                'skpi_poin' => 10,
            ]
        );

        $eventC = Event::updateOrCreate(
            ['nama_event' => 'Open Recruitment & Temu Karya UKM Kampus', 'created_by' => $bem->id],
            [
                'kategori' => 'Kegiatan UKM',
                'tanggal' => Carbon::today()->addDays(20)->format('Y-m-d'),
                'waktu_mulai' => '15:00',
                'waktu_selesai' => '17:30',
                'lokasi' => 'Student Center Kampus',
                'deskripsi' => 'Ajang pengenalan berbagai Unit Kegiatan Mahasiswa dan pameran karya kreatif mahasiswa baru.',
                'poster' => 'posters/1783481906_gpt-image-2_Vibrant_Indonesian_cultural_festival_poster_traditional_wayang_kulit_silhouette_-0.jpg',
                'kuota' => 30,
                'harga' => 0,
                'skpi_poin' => 5,
            ]
        );

        $eventD = Event::updateOrCreate(
            ['nama_event' => 'Kompetisi Hackathon Inovasi Mahasiswa HIMATIF', 'created_by' => $himatif->id],
            [
                'kategori' => 'Lomba & Kompetisi',
                'tanggal' => Carbon::today()->addDays(12)->format('Y-m-d'),
                'waktu_mulai' => '08:30',
                'waktu_selesai' => '16:00',
                'lokasi' => 'Gedung GSC Lantai 5',
                'deskripsi' => 'Kompetisi pengembangan prototipe solusi digital kampus yang diselenggarakan oleh HIMATIF.',
                'poster' => 'posters/1783481836_gpt-image-2_Cyberpunk_coding_competition_poster_dark_background_with_green_matrix_code_rain_-0.jpg',
                'kuota' => 40,
                'harga' => 15000,
                'skpi_poin' => 10,
            ]
        );

        $eventE = Event::updateOrCreate(
            ['nama_event' => 'Webinar Kepemimpinan & Manajemen Organisasi Kampus', 'created_by' => $bem->id],
            [
                'kategori' => 'Seminar & Webinar',
                'tanggal' => Carbon::today()->subDays(7)->format('Y-m-d'),
                'waktu_mulai' => '19:00',
                'waktu_selesai' => '21:00',
                'lokasi' => 'Online (Zoom Meeting)',
                'deskripsi' => 'Webinar penguatan kapasitas kepemimpinan, komunikasi efektif, dan tata kelola organisasi kemahasiswaan.',
                'poster' => 'posters/1783481510_gpt-image-2_Professional_webinar_poster_with_digital_career_theme_laptop_with_growth_graph_a-0.jpg',
                'kuota' => 100,
                'harga' => 0,
                'skpi_poin' => 20,
            ]
        );

        // 5. Participants (4 status: registered, confirmed, attended, canceled)
        // Event A: mhs1 (registered), mhs2 (confirmed), mhs3 (canceled)
        Participant::updateOrCreate(
            ['event_id' => $eventA->id, 'user_id' => $mhs1->id],
            ['status' => 'registered']
        );
        Participant::updateOrCreate(
            ['event_id' => $eventA->id, 'user_id' => $mhs2->id],
            ['status' => 'confirmed']
        );
        Participant::updateOrCreate(
            ['event_id' => $eventA->id, 'user_id' => $mhs3->id],
            ['status' => 'canceled']
        );

        // Event B (Penuh 2/2): mhs2 (confirmed), mhs3 (registered)
        Participant::updateOrCreate(
            ['event_id' => $eventB->id, 'user_id' => $mhs2->id],
            ['status' => 'confirmed']
        );
        Participant::updateOrCreate(
            ['event_id' => $eventB->id, 'user_id' => $mhs3->id],
            ['status' => 'registered']
        );

        // Event C: Sengaja kosong (tanpa peserta) untuk uji hapus event

        // Event D (HIMATIF - Anti-IDOR): mhs1 (confirmed)
        Participant::updateOrCreate(
            ['event_id' => $eventD->id, 'user_id' => $mhs1->id],
            ['status' => 'confirmed']
        );

        // Event E (Lampau / Selesai): mhs1 (attended), mhs2 (attended)
        Participant::updateOrCreate(
            ['event_id' => $eventE->id, 'user_id' => $mhs1->id],
            ['status' => 'attended']
        );
        Participant::updateOrCreate(
            ['event_id' => $eventE->id, 'user_id' => $mhs2->id],
            ['status' => 'attended']
        );
    }
}

