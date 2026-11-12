<?php

namespace App\Filament\Resources\Skills\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class SkillForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->columns(2)
            ->components([
                TextInput::make('name')->label('Nama skill')->required()->maxLength(100),
                TextInput::make('level_label')->label('Label level')->maxLength(50)->placeholder('mis. Luar Biasa'),
                TextInput::make('percentage')->label('Persentase')->required()->integer()->minValue(0)->maxValue(100)->suffix('%')->default(80),
                Toggle::make('is_visible')->label('Tampilkan di portfolio')->default(true),
            ]);
    }
}
