<?php

namespace Database\Seeders;

use App\Models\Certification;
use App\Models\Education;
use App\Models\Experience;
use App\Models\Organization;
use App\Models\Profile;
use App\Models\Project;
use App\Models\Skill;
use App\Models\Stat;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

/**
 * Konten awal portfolio, diambil dari versi statis (legacy/index.html).
 * Aman dijalankan ulang: hanya mengisi jika tabel profil masih kosong.
 */
class PortfolioSeeder extends Seeder
{
    public function run(): void
    {
        $this->seedProjects();

        if (Profile::query()->exists()) {
            $this->command?->warn('Data portfolio sudah ada, seeder dilewati.');

            return;
        }

        DB::transaction(function () {
            Profile::create([
                'logo_text' => 'MFR',
                'name' => "Muh. Fery\nFatchan Rizqi",
                'badge_text' => '✦ Available for Work',
                'subtitle' => 'IT Professional | Web Developer | Security Specialist',
                'description' => 'Profesional IT berpengalaman dalam pengembangan software, keamanan sistem, dan infrastruktur jaringan. Spesialisasi dalam membangun solusi web yang inovatif dan efisien untuk industri telekomunikasi.',
                'email' => 'feryfatchan361@gmail.com',
                'phone' => '+62 877-6388-2225',
                'whatsapp_number' => '6287763882225',
                'linkedin_url' => 'https://www.linkedin.com/in/muh-fery-fatchanrizqi-632249347',
                'address' => 'Jl. Jendral Sudirman Gg Marga Bhakti No 100, Pekalongan Barat, Kab. Pekalongan',
                'skills_subtitle' => 'Menguasai berbagai teknologi dan tools untuk pengembangan software dan infrastruktur IT',
                'experience_subtitle' => 'Pengalaman profesional di PT. Telekomunikasi Indonesia',
                'organization_subtitle' => 'Pengalaman kepemimpinan dan organisasi',
                'projects_subtitle' => 'Sistem yang saya rancang dan bangun',
                'education_subtitle' => 'Latar belakang pendidikan tinggi',
                'certification_subtitle' => 'Sertifikasi profesional di bidang IT Security',
                'contact_title' => "Let's Connect",
                'contact_subtitle' => 'Tertarik untuk berkolaborasi? Mari berdiskusi tentang proyek Anda',
            ]);

            $this->createOrdered(Stat::class, [
                ['number' => '3.31', 'label' => 'GPA Score'],
                ['number' => '8+', 'label' => 'Technical Skills'],
                ['number' => '7+', 'label' => 'Years Experience'],
                ['number' => '2', 'label' => 'Certifications'],
            ]);

            $this->createOrdered(Skill::class, [
                ['name' => 'Microsoft Word', 'level_label' => 'Luar Biasa', 'percentage' => 95],
                ['name' => 'Microsoft Excel', 'level_label' => 'Unggul', 'percentage' => 85],
                ['name' => 'MySQL Database', 'level_label' => 'Luar Biasa', 'percentage' => 95],
                ['name' => 'Design Grafis', 'level_label' => 'Luar Biasa', 'percentage' => 95],
                ['name' => 'IT Security', 'level_label' => 'Unggul', 'percentage' => 85],
                ['name' => 'Software Development', 'level_label' => 'Luar Biasa', 'percentage' => 95],
                ['name' => 'Hardware Infrastructure', 'level_label' => 'Unggul', 'percentage' => 85],
                ['name' => 'Network & IT', 'level_label' => 'Luar Biasa', 'percentage' => 95],
            ]);

            $this->createOrdered(Experience::class, [
                [
                    'title' => 'Web Developer',
                    'company' => 'PT. Telekomunikasi Indonesia - Telda Slawi',
                    'location' => 'Kabupaten Tegal, Indonesia',
                    'period' => 'Jun 2024 - Feb 2026',
                    'description' => 'Merancang dan membangun sistem MyStar (My Sales Team) untuk pengelolaan data tim penjualan secara terintegrasi. Bertanggung jawab atas perancangan arsitektur sistem, database design, pengembangan fitur dashboard dan laporan real-time, serta pemeliharaan untuk memastikan sistem berjalan optimal dan user-friendly.',
                ],
                [
                    'title' => 'Marketing',
                    'company' => 'PT. Telekomunikasi Indonesia',
                    'location' => 'Kabupaten Tegal, Indonesia',
                    'period' => 'Jul 2024 - Feb 2026',
                    'description' => 'Mengelola hubungan perusahaan dengan pelanggan, memastikan merek dipersepsikan sebagai ramah, bermanfaat, dan bernilai. Membangun dan memelihara hubungan kuat dengan pelanggan untuk meningkatkan loyalitas dan kepuasan.',
                ],
                [
                    'title' => 'Teknisi Jaringan',
                    'company' => 'PT. Telekomunikasi Indonesia - Datel Batang',
                    'location' => 'Kabupaten Batang, Indonesia',
                    'period' => 'Okt 2018 - Apr 2019',
                    'description' => 'Melakukan instalasi jaringan Fiber Optic kepada pelanggan, maintenance Valins ODP, serta troubleshooting untuk menyelesaikan masalah teknis pelanggan dengan cepat dan efektif.',
                ],
            ]);

            $this->createOrdered(Organization::class, [
                [
                    'title' => 'Ketua Bidang Perencanaan & Pengembangan',
                    'organization' => 'Pencak Silat Pagar Nusa',
                    'description' => 'Menyusun program kerja bidang pencak silat Pagar Nusa secara periodik. Bertanggung jawab dalam perencanaan strategis dan pengembangan organisasi untuk mencapai target yang telah ditetapkan.',
                ],
            ]);

            $this->createOrdered(Education::class, [
                [
                    'institution' => 'STMIK Widya Pratama Pekalongan',
                    'degree' => 'S1 - Sistem Informasi',
                    'period' => '2021 - 2026',
                    'faculty' => 'Fakultas Sistem Informasi',
                    'location' => 'Kabupaten Pekalongan, Indonesia',
                    'gpa' => 3.31,
                    'gpa_scale' => 4.00,
                ],
            ]);

            $this->createOrdered(Certification::class, [
                ['name' => 'IT Security Certification Preparation', 'icon' => '🔒', 'score' => '75.00'],
                ['name' => 'Classical Cryptography for Beginner Course', 'icon' => '🔐', 'score' => '80.00'],
            ]);
        });
    }

    /** Proyek diisi terpisah agar instalasi lama yang sudah punya profil tetap mendapat contoh proyek. */
    private function seedProjects(): void
    {
        if (Project::query()->exists()) {
            return;
        }

        Project::create([
            'title' => 'MyStar (My Sales Team)',
            'role' => 'Web Developer',
            'period' => 'Jun 2024 - Feb 2026',
            'summary' => 'Sistem pengelolaan data tim penjualan yang terintegrasi untuk PT. Telekomunikasi Indonesia - Telda Slawi.',
            'description' => 'Perancangan arsitektur sistem, desain database, pengembangan dashboard dan laporan real-time, serta pemeliharaan agar sistem berjalan optimal dan mudah digunakan.',
            'tech_stack' => ['Laravel', 'MySQL', 'Dashboard', 'Laporan real-time'],
            'sort_order' => 1,
            'is_visible' => true,
        ]);
    }

    /**
     * @param  class-string<Model>  $model
     * @param  array<int, array<string, mixed>>  $rows
     */
    private function createOrdered(string $model, array $rows): void
    {
        foreach ($rows as $index => $row) {
            $model::create($row + ['sort_order' => $index + 1, 'is_visible' => true]);
        }
    }
}
