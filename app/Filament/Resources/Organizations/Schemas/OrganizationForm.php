<?php

namespace App\Filament\Resources\Organizations\Schemas;

use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class OrganizationForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->columns(2)
            ->components([
                TextInput::make('title')->label('Jabatan')->required()->maxLength(255),
                TextInput::make('organization')->label('Organisasi')->required()->maxLength(255),
                TextInput::make('period')->label('Periode')->maxLength(100),
                Textarea::make('description')->label('Deskripsi')->rows(5)->maxLength(5000)->columnSpanFull(),
                Toggle::make('is_visible')->label('Tampilkan di portfolio')->default(true),
            ]);
    }
}
