<?php

namespace Tests\Feature;

use App\Filament\Pages\EditPortfolioProfile;
use App\Filament\Resources\Projects\Pages\CreateProject;
use App\Models\Profile;
use App\Models\Project;
use App\Models\User;
use Database\Seeders\PortfolioSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Livewire\Livewire;
use Tests\TestCase;

class MediaAndProjectsTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        Storage::fake('public');
        $this->seed(PortfolioSeeder::class);
    }

    public function test_seeded_project_is_shown_on_portfolio(): void
    {
        $this->get('/')->assertOk()->assertSee('MyStar (My Sales Team)')->assertSee('Laravel');
    }

    public function test_admin_can_create_project_with_image(): void
    {
        $this->actingAs(User::factory()->admin()->create());

        Livewire::test(CreateProject::class)
            ->fillForm([
                'title' => 'Monitoring Jaringan',
                'tech_stack' => ['MikroTik', 'Grafana'],
                'image_path' => UploadedFile::fake()->image('shot.png', 1600, 1000),
                'url' => 'https://example.com',
            ])
            ->call('create')
            ->assertHasNoFormErrors();

        $project = Project::where('title', 'Monitoring Jaringan')->firstOrFail();
        $this->assertSame(['MikroTik', 'Grafana'], $project->tech_stack);
        Storage::disk('public')->assertExists($project->image_path);

        $this->get('/')->assertSee('Monitoring Jaringan')->assertSee('Grafana')->assertSee($project->imageUrl(), false);
    }

    public function test_project_rejects_non_image_upload(): void
    {
        $this->actingAs(User::factory()->admin()->create());

        Livewire::test(CreateProject::class)
            ->fillForm([
                'title' => 'Berbahaya',
                'image_path' => UploadedFile::fake()->create('evil.svg', 10, 'image/svg+xml'),
            ])
            ->call('create')
            ->assertHasFormErrors(['image_path']);
    }

    public function test_admin_can_upload_photo_and_cv(): void
    {
        $this->actingAs(User::factory()->admin()->create());

        Livewire::test(EditPortfolioProfile::class)
            ->fillForm([
                'photo_path' => UploadedFile::fake()->image('me.jpg', 800, 1000),
                'cv_path' => UploadedFile::fake()->create('cv.pdf', 200, 'application/pdf'),
            ])
            ->call('save')
            ->assertHasNoFormErrors();

        $profile = Profile::current();
        Storage::disk('public')->assertExists($profile->photo_path);
        Storage::disk('public')->assertExists($profile->cv_path);

        $this->get('/')->assertSee($profile->photoUrl(), false)->assertSee('Unduh CV');
    }

    public function test_cv_must_be_pdf(): void
    {
        $this->actingAs(User::factory()->admin()->create());

        Livewire::test(EditPortfolioProfile::class)
            ->fillForm(['cv_path' => UploadedFile::fake()->create('cv.exe', 10, 'application/x-msdownload')])
            ->call('save')
            ->assertHasFormErrors(['cv_path']);
    }

    public function test_cv_button_hidden_without_cv(): void
    {
        $this->get('/')->assertOk()->assertDontSee('Unduh CV');
    }

    public function test_replacing_or_deleting_files_removes_old_ones(): void
    {
        $disk = Storage::disk('public');
        $disk->put('profile/old.jpg', 'x');
        $disk->put('profile/new.jpg', 'y');
        $disk->put('projects/p.png', 'z');

        $profile = Profile::current();
        $profile->update(['photo_path' => 'profile/old.jpg']);
        $profile->update(['photo_path' => 'profile/new.jpg']);
        $disk->assertMissing('profile/old.jpg');
        $disk->assertExists('profile/new.jpg');

        $project = Project::create(['title' => 'Hapus saya', 'image_path' => 'projects/p.png']);
        $project->delete();
        $disk->assertMissing('projects/p.png');
    }
}
