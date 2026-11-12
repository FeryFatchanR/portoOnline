<?php

namespace Tests\Feature;

use App\Models\Profile;
use App\Models\Skill;
use Database\Seeders\PortfolioSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PortfolioPageTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_can_view_portfolio_with_seeded_content(): void
    {
        $this->seed(PortfolioSeeder::class);

        $this->get('/')
            ->assertOk()
            ->assertSee('Fatchan Rizqi')
            ->assertSee('MySQL Database')
            ->assertSee('PT. Telekomunikasi Indonesia - Telda Slawi')
            ->assertSee('Pencak Silat Pagar Nusa')
            ->assertSee('STMIK Widya Pratama Pekalongan')
            ->assertSee('Classical Cryptography for Beginner Course');
    }

    public function test_portfolio_renders_when_database_is_empty(): void
    {
        $this->get('/')->assertOk();
    }

    public function test_hidden_items_are_not_shown(): void
    {
        $this->seed(PortfolioSeeder::class);
        Skill::create(['name' => 'Skill Rahasia', 'percentage' => 50, 'is_visible' => false]);

        $this->get('/')->assertOk()->assertDontSee('Skill Rahasia');
    }

    public function test_content_is_html_escaped(): void
    {
        Profile::current()->update(['name' => '<script>alert(1)</script>']);

        $this->get('/')
            ->assertOk()
            ->assertDontSee('<script>alert(1)</script>', false)
            ->assertSee('&lt;script&gt;alert(1)&lt;/script&gt;', false);
    }

    public function test_seeder_is_idempotent(): void
    {
        $this->seed(PortfolioSeeder::class);
        $this->seed(PortfolioSeeder::class);

        $this->assertSame(1, Profile::count());
        $this->assertSame(8, Skill::count());
    }

    public function test_new_items_are_appended_to_the_end(): void
    {
        $this->seed(PortfolioSeeder::class);

        $skill = Skill::create(['name' => 'Laravel', 'percentage' => 90]);

        $this->assertSame(9, $skill->sort_order);
    }

    public function test_page_is_personal_not_company(): void
    {
        $this->seed(PortfolioSeeder::class);

        $this->get('/')
            ->assertOk()
            ->assertSee('Membangun aplikasi web dan jaringan yang andal dan aman')
            ->assertSee('Hubungi saya')
            ->assertDontSee('Layanan')
            ->assertDontSee(' kami');
    }

    public function test_headline_falls_back_to_subtitle(): void
    {
        Profile::current()->update(['tagline' => null, 'subtitle' => 'Network Engineer']);

        $this->get('/')->assertOk()->assertSee('<h1>Network Engineer</h1>', false);
    }
}
