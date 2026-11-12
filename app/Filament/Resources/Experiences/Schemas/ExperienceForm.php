<?php

namespace App\Filament\Resources\Experiences\Schemas;

use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class ExperienceForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->columns(2)
            ->components([
                TextInput::make('title')->label('Jabatan')->required()->maxLength(255),
                TextInput::make('company')->label('Perusahaan')->required()->maxLength(255),
                TextInput::make('location')->label('Lokasi')->maxLength(255),
                TextInput::make('period')->label('Periode')->maxLength(100)->placeholder('mis. Jun 2024 - Feb 2026'),
                Textarea::make('description')->label('Deskripsi')->rows(5)->maxLength(5000)->columnSpanFull(),
                Toggle::make('is_visible')->label('Tampilkan di portfolio')->default(true),
            ]);
    }
}
