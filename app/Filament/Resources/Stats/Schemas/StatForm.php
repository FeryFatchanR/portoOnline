<?php

namespace App\Filament\Resources\Stats\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class StatForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->columns(2)
            ->components([
                TextInput::make('number')->label('Angka')->required()->maxLength(20)->placeholder('mis. 7+'),
                TextInput::make('label')->label('Keterangan')->required()->maxLength(100),
                Toggle::make('is_visible')->label('Tampilkan di portfolio')->default(true),
            ]);
    }
}
