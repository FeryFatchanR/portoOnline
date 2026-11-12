<?php

namespace App\Filament\Resources\Education\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class EducationForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->columns(2)
            ->components([
                TextInput::make('institution')->label('Institusi')->required()->maxLength(255),
                TextInput::make('degree')->label('Jenjang / Jurusan')->maxLength(255)->placeholder('mis. S1 - Sistem Informasi'),
                TextInput::make('period')->label('Periode')->maxLength(100),
                TextInput::make('faculty')->label('Fakultas')->maxLength(255),
                TextInput::make('location')->label('Lokasi')->maxLength(255),
                TextInput::make('gpa')->label('IPK')->numeric()->minValue(0)->maxValue(99.99)->step(0.01),
                TextInput::make('gpa_scale')->label('Skala IPK')->numeric()->required()->minValue(1)->maxValue(99.99)->step(0.01)->default(4),
                Toggle::make('is_visible')->label('Tampilkan di portfolio')->default(true),
            ]);
    }
}
