<?php

namespace App\Filament\Resources\TeamMembers\Schemas;

use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class TeamMemberForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->columns(2)
            ->components([
                TextInput::make('name')->label('Nama')->required()->maxLength(255),
                TextInput::make('position')->label('Jabatan')->maxLength(255),
                Textarea::make('bio')->label('Bio singkat')->rows(3)->maxLength(500)->columnSpanFull(),
                FileUpload::make('photo_path')
                    ->label('Foto')
                    ->image()
                    ->acceptedFileTypes(['image/jpeg', 'image/png', 'image/webp'])
                    ->maxSize(2048)
                    ->disk('public')
                    ->directory('team')
                    ->visibility('public')
                    ->imageEditor()
                    ->helperText('JPG, PNG, atau WebP, maks. 2 MB.'),
                TextInput::make('linkedin_url')->label('URL LinkedIn')->url()->maxLength(255),
                Toggle::make('is_visible')->label('Tampilkan di website')->default(true),
            ]);
    }
}
