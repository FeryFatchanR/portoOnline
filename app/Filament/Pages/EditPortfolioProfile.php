<?php

namespace App\Filament\Pages;

use App\Models\Profile;
use BackedEnum;
use Filament\Actions\Action;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Filament\Schemas\Components\Actions;
use Filament\Schemas\Components\EmbeddedSchema;
use Filament\Schemas\Components\Form;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use UnitEnum;

/**
 * Halaman edit profil portfolio (satu baris data di tabel profiles).
 *
 * @property-read Schema $form
 */
class EditPortfolioProfile extends Page
{
    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedUserCircle;

    protected static string|UnitEnum|null $navigationGroup = 'Konten Website';

    protected static ?int $navigationSort = 1;

    protected static ?string $navigationLabel = 'Profil Usaha & Kontak';

    protected static ?string $title = 'Profil Usaha & Kontak';

    protected static ?string $slug = 'profile';

    /** @var array<string, mixed>|null */
    public ?array $data = [];

    public function mount(): void
    {
        $this->form->fill(Profile::current()->attributesToArray());
    }

    public function form(Schema $schema): Schema
    {
        return $schema
            ->model(Profile::current())
            ->statePath('data')
            ->components([
                Section::make('Identitas usaha')
                    ->description('Kosongkan nama usaha jika ingin memakai nama Anda sendiri sebagai brand.')
                    ->columns(2)
                    ->schema([
                        TextInput::make('company_name')->label('Nama usaha / brand')->maxLength(255)->placeholder('mis. MFR Tech Solutions'),
                        TextInput::make('tagline')->label('Tagline (judul besar di halaman depan)')->maxLength(255),
                        FileUpload::make('logo_path')
                            ->label('Logo')
                            ->image()
                            ->acceptedFileTypes(['image/png', 'image/webp', 'image/jpeg'])
                            ->maxSize(1024)
                            ->disk('public')
                            ->directory('brand')
                            ->visibility('public')
                            ->helperText('PNG/WebP transparan disarankan, maks. 1 MB. Jika kosong, dipakai teks logo.'),
                    ]),
                Section::make('Pemilik / pendiri')
                    ->columns(2)
                    ->schema([
                        Textarea::make('name')
                            ->label('Nama')
                            ->helperText('Tekan Enter untuk memecah nama menjadi dua baris.')
                            ->required()->rows(2)->maxLength(255),
                        TextInput::make('logo_text')->label('Teks logo (navbar)')->required()->maxLength(10),
                        TextInput::make('badge_text')->label('Badge')->maxLength(255),
                        TextInput::make('subtitle')->label('Subjudul')->maxLength(255),
                        Textarea::make('description')->label('Deskripsi singkat')->rows(4)->maxLength(2000)->columnSpanFull(),
                    ]),
                Section::make('Foto & CV')
                    ->columns(2)
                    ->schema([
                        FileUpload::make('photo_path')
                            ->label('Foto profil')
                            ->image()
                            ->acceptedFileTypes(['image/jpeg', 'image/png', 'image/webp'])
                            ->maxSize(2048)
                            ->disk('public')
                            ->directory('profile')
                            ->visibility('public')
                            ->imageEditor()
                            ->imageEditorAspectRatioOptions(['4:5', '1:1'])
                            ->helperText('JPG, PNG, atau WebP, maks. 2 MB. Potret 4:5 paling pas.'),
                        FileUpload::make('cv_path')
                            ->label('File CV')
                            ->acceptedFileTypes(['application/pdf'])
                            ->maxSize(5120)
                            ->disk('public')
                            ->directory('cv')
                            ->visibility('public')
                            ->downloadable()
                            ->helperText('PDF, maks. 5 MB. Pengunjung bisa mengunduhnya dari tombol "Unduh CV".'),
                    ]),
                Section::make('Kontak')
                    ->columns(2)
                    ->schema([
                        TextInput::make('email')->label('Email')->email()->maxLength(255),
                        TextInput::make('phone')->label('Telepon (tampilan)')->tel()->maxLength(255),
                        TextInput::make('whatsapp_number')
                            ->label('Nomor WhatsApp')
                            ->helperText('Format internasional tanpa + atau spasi, mis. 6281234567890.')
                            ->regex('/^[0-9]{8,20}$/')->maxLength(20),
                        TextInput::make('linkedin_url')->label('URL LinkedIn')->url()->maxLength(255),
                        TextInput::make('address')->label('Alamat (footer)')->maxLength(255)->columnSpanFull(),
                        TextInput::make('contact_title')->label('Judul bagian kontak')->maxLength(255),
                        TextInput::make('contact_subtitle')->label('Subjudul bagian kontak')->maxLength(255),
                    ]),
                Section::make('Subjudul tiap bagian')
                    ->columns(2)
                    ->collapsed()
                    ->schema([
                        TextInput::make('services_subtitle')->label('Layanan')->maxLength(255),
                        TextInput::make('team_subtitle')->label('Tim')->maxLength(255),
                        TextInput::make('skills_subtitle')->label('Skills')->maxLength(255),
                        TextInput::make('experience_subtitle')->label('Pengalaman kerja')->maxLength(255),
                        TextInput::make('projects_subtitle')->label('Proyek')->maxLength(255),
                        TextInput::make('organization_subtitle')->label('Organisasi')->maxLength(255),
                        TextInput::make('education_subtitle')->label('Pendidikan')->maxLength(255),
                        TextInput::make('certification_subtitle')->label('Sertifikasi')->maxLength(255),
                    ]),
            ]);
    }

    public function content(Schema $schema): Schema
    {
        return $schema->components([
            Form::make([EmbeddedSchema::make('form')])
                ->id('form')
                ->livewireSubmitHandler('save')
                ->footer([
                    Actions::make([
                        Action::make('save')
                            ->label('Simpan')
                            ->submit('save')
                            ->keyBindings(['mod+s']),
                    ]),
                ]),
        ]);
    }

    public function save(): void
    {
        $data = $this->form->getState();

        Profile::current()->update($data);

        Notification::make()->success()->title('Profil berhasil disimpan')->send();
    }
}
