<?php

namespace Tests\Feature;

use App\Filament\Pages\EditPortfolioProfile;
use App\Filament\Resources\Skills\Pages\CreateSkill;
use App\Filament\Resources\Skills\Pages\EditSkill;
use App\Models\Profile;
use App\Models\Skill;
use App\Models\User;
use Database\Seeders\PortfolioSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class AdminContentManagementTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->seed(PortfolioSeeder::class);
        $this->actingAs(User::factory()->admin()->create());
    }

    public function test_admin_can_create_skill_and_it_appears_on_portfolio(): void
    {
        Livewire::test(CreateSkill::class)
            ->fillForm(['name' => 'Laravel Filament', 'level_label' => 'Mahir', 'percentage' => 90])
            ->call('create')
            ->assertHasNoFormErrors();

        $this->assertDatabaseHas('skills', ['name' => 'Laravel Filament', 'percentage' => 90, 'is_visible' => true]);
        $this->get('/')->assertSee('Laravel Filament');
    }

    public function test_skill_percentage_is_validated(): void
    {
        Livewire::test(CreateSkill::class)
            ->fillForm(['name' => 'Invalid', 'percentage' => 150])
            ->call('create')
            ->assertHasFormErrors(['percentage']);

        $this->assertDatabaseMissing('skills', ['name' => 'Invalid']);
    }

    public function test_admin_can_edit_skill(): void
    {
        $skill = Skill::where('name', 'IT Security')->firstOrFail();

        Livewire::test(EditSkill::class, ['record' => $skill->getRouteKey()])
            ->fillForm(['percentage' => 99])
            ->call('save')
            ->assertHasNoFormErrors();

        $this->assertSame(99, $skill->fresh()->percentage);
    }

    public function test_admin_can_update_profile(): void
    {
        Livewire::test(EditPortfolioProfile::class)
            ->fillForm(['subtitle' => 'Network & Security Engineer'])
            ->call('save')
            ->assertHasNoFormErrors();

        $this->assertSame('Network & Security Engineer', Profile::current()->subtitle);
        $this->get('/')->assertSee('Network &amp; Security Engineer', false);
    }

    public function test_profile_validates_urls_and_whatsapp(): void
    {
        Livewire::test(EditPortfolioProfile::class)
            ->fillForm(['linkedin_url' => 'javascript:alert(1)', 'whatsapp_number' => '+62 abc'])
            ->call('save')
            ->assertHasFormErrors(['linkedin_url', 'whatsapp_number']);
    }
}
