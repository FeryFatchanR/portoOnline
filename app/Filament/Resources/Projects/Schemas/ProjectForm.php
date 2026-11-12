<?php

namespace App\Filament\Resources\Projects\Schemas;

use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\TagsInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class ProjectForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->columns(2)
            ->components([
                TextInput::make('title')->label('Nama proyek')->required()->maxLength(255),
                TextInput::make('role')->label('Peran')->maxLength(255)->placeholder('mis. Full-stack Developer'),
                TextInput::make('period')->label('Periode')->maxLength(100)->placeholder('mis. Jun 2024 - Feb 2026'),
                TagsInput::make('tech_stack')->label('Teknologi')->placeholder('Ketik lalu Enter')->splitKeys([','])->nestedRecursiveRules(['max:40']),
                Textarea::make('summary')->label('Ringkasan (1-2 kalimat)')->rows(2)->maxLength(500)->columnSpanFull(),
                Textarea::make('description')->label('Deskripsi lengkap')->rows(5)->maxLength(5000)->columnSpanFull(),
                FileUpload::make('image_path')
                    ->label('Gambar / screenshot')
                    ->image()
                    ->acceptedFileTypes(['image/jpeg', 'image/png', 'image/webp'])
                    ->maxSize(3072)
                    ->disk('public')
                    ->directory('projects')
                    ->visibility('public')
                    ->imageEditor()
                    ->helperText('JPG, PNG, atau WebP, maks. 3 MB. Rasio 16:10 paling pas.')
                    ->columnSpanFull(),
                TextInput::make('url')->label('URL situs / demo')->url()->maxLength(255),
                TextInput::make('repo_url')->label('URL repository')->url()->maxLength(255),
                Toggle::make('is_visible')->label('Tampilkan di portfolio')->default(true),
            ]);
    }
}
