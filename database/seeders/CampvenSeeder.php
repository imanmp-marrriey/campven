<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Event;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class CampvenSeeder extends Seeder
{
    public function run(): void
    {
        // Categories
        $categories = [
            ['nama' => 'Seminar', 'icon' => 'fa-graduation-cap', 'color' => '#6C63FF', 'description' => 'Diskusi dan presentasi tentang topik akademik dan profesional'],
            ['nama' => 'Workshop', 'icon' => 'fa-tools', 'color' => '#10B981', 'description' => 'Pelatihan praktis dan hands-on untuk mengembangkan skill'],
            ['nama' => 'Lomba', 'icon' => 'fa-trophy', 'color' => '#F59E0B', 'description' => 'Kompetisi antar peserta di berbagai bidang'],
            ['nama' => 'Webinar', 'icon' => 'fa-video', 'color' => '#3B82F6', 'description' => 'Seminar online yang bisa diikuti dari mana saja'],
            ['nama' => 'Festival', 'icon' => 'fa-music', 'color' => '#EF4444', 'description' => 'Event seni, budaya, dan hiburan'],
            ['nama' => 'Olahraga', 'icon' => 'fa-running', 'color' => '#14B8A6', 'description' => 'Kegiatan olahraga dan kompetisi fisik'],
            ['nama' => 'Lainnya', 'icon' => 'fa-ellipsis-h', 'color' => '#6B7280', 'description' => 'Event-event lainnya'],
        ];

        foreach ($categories as $cat) {
            Category::firstOrCreate(['nama' => $cat['nama']], $cat);
        }

        // Users (password = 'password')
        $users = [
            ['nama' => 'Admin Utama', 'email' => 'admin@campven.com', 'password' => Hash::make('password'), 'role' => 'admin'],
            ['nama' => 'BEM Polindra', 'email' => 'bem@campven.com', 'password' => Hash::make('password'), 'role' => 'penyelenggara'],
            ['nama' => 'HIMTI', 'email' => 'himti@campven.com', 'password' => Hash::make('password'), 'role' => 'penyelenggara'],
            ['nama' => 'Raka Pratama', 'email' => 'mahasiswa1@campven.com', 'password' => Hash::make('password'), 'role' => 'mahasiswa'],
            ['nama' => 'Siti Aulia', 'email' => 'mahasiswa2@campven.com', 'password' => Hash::make('password'), 'role' => 'mahasiswa'],
            ['nama' => 'Budi Santoso', 'email' => 'mahasiswa3@campven.com', 'password' => Hash::make('password'), 'role' => 'mahasiswa'],
        ];

        $userMap = [];
        foreach ($users as $user) {
            $u = User::firstOrCreate(['email' => $user['email']], $user);
            $userMap[$user['email']] = $u->id;
        }

        // Events
        $events = [
            ['nama_event' => 'Seminar AI 2026', 'kategori' => 'Seminar', 'tanggal' => '2026-07-20', 'waktu_mulai' => '09:00', 'waktu_selesai' => '12:00', 'lokasi' => 'GSC Lantai 7, Politeknik Negeri Indramayu', 'deskripsi' => 'Seminar tentang perkembangan Artificial Intelligence', 'created_by' => $userMap['bem@campven.com'], 'kuota' => 100],
            ['nama_event' => 'Workshop Flutter', 'kategori' => 'Workshop', 'tanggal' => '2026-07-25', 'waktu_mulai' => '13:00', 'waktu_selesai' => '17:00', 'lokasi' => 'Lab Komputer, Politeknik Negeri Indramayu', 'deskripsi' => 'Workshop praktis pengembangan aplikasi mobile dengan Flutter', 'created_by' => $userMap['bem@campven.com'], 'kuota' => 30],
            ['nama_event' => 'Lomba Inovasi', 'kategori' => 'Lomba', 'tanggal' => '2026-08-01', 'waktu_mulai' => '08:00', 'waktu_selesai' => '16:00', 'lokasi' => 'Aula Utama, Politeknik Negeri Indramayu', 'deskripsi' => 'Lomba inovasi antar mahasiswa', 'created_by' => $userMap['himti@campven.com'], 'kuota' => 50],
            ['nama_event' => 'Webinar Karir', 'kategori' => 'Webinar', 'tanggal' => '2026-08-05', 'waktu_mulai' => '19:00', 'waktu_selesai' => '21:00', 'lokasi' => 'Online (Zoom)', 'deskripsi' => 'Webinar membahas peluang karir di bidang digital', 'created_by' => $userMap['bem@campven.com'], 'kuota' => null],
        ];

        foreach ($events as $event) {
            Event::firstOrCreate(['nama_event' => $event['nama_event']], $event);
        }
    }
}