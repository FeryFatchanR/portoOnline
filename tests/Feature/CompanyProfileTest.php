<?php

namespace Tests\Feature;

use App\Filament\Resources\Services\Pages\CreateService;
use App\Filament\Resources\TeamMembers\Pages\CreateTeamMember;
use App\Models\Profile;
use App\Models\Service;
use App\Models\TeamMember;
use App\Models\User;
use Database\Seeders\PortfolioSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Livewire\Livewire;
use Tests\TestCase;

class CompanyProfileTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        Storage::fake('public');
        $this->seed(PortfolioSeeder::class);
    }

    public function test_seeded_services_are_shown(): void
    {
        $this->get('/')
            ->assertOk()
            ->assertSee('Pembuatan Website &amp; Aplikasi', false)
            ->assertSee('Instalasi &amp; Manajemen Jaringan', false)
            ->assertSee('Konfigurasi router MikroTik')
            ->assertSee('Solusi website, jaringan, dan keamanan IT untuk bisnis Anda');
    }

    public function test_brand_falls_back_to_person_name_and_uses_company_name_when_set(): void
    {
        $this->get('/')->assertSee('<span class="brand-name">Muh. Fery Fatchan Rizqi</span>', false);

        Profile::current()->update(['company_name' => 'MFR Tech Solutions']);

        $this->get('/')
            ->assertSee('<span class="brand-name">MFR Tech Solutions</span>', false)
            ->assertSee('Di balik MFR Tech Solutions');
    }

    public function test_team_section_only_appears_with_members(): void
    {
        $this->get('/')->assertDontSee('id="tim"', false);

        TeamMember::create(['name' => 'Rina Saputri', 'position' => 'UI Designer']);

        $this->get('/')->assertSee('id="tim"', false)->assertSee('Rina Saputri')->assertSee('UI Designer');
    }

    public function test_admin_can_create_service_and_team_member(): void
    {
        $this->actingAs(User::factory()->admin()->create());

        Livewire::test(CreateService::class)
            ->fillForm(['title' => 'Maintenance Server', 'icon' => 'server', 'features' => ['Monitoring 24/7']])
            ->call('create')
            ->assertHasNoFormErrors();

        Livewire::test(CreateTeamMember::class)
            ->fillForm(['name' => 'Budi', 'position' => 'Teknisi', 'photo_path' => UploadedFile::fake()->image('b.jpg')])
            ->call('create')
            ->assertHasNoFormErrors();

        $this->assertSame(['Monitoring 24/7'], Service::where('title', 'Maintenance Server')->first()->features);
        Storage::disk('public')->assertExists(TeamMember::where('name', 'Budi')->first()->photo_path);
        $this->get('/')->assertSee('Maintenance Server')->assertSee('Monitoring 24/7')->assertSee('Budi');
    }

    public function test_service_icon_must_be_from_list(): void
    {
        $this->actingAs(User::factory()->admin()->create());

        Livewire::test(CreateService::class)
            ->fillForm(['title' => 'X', 'icon' => '<script>'])
            ->call('create')
            ->assertHasFormErrors(['icon']);
    }
}
