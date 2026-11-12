<?php

namespace App\Filament\Resources\Certifications\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class CertificationForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->columns(2)
            ->components([
                TextInput::make('name')->label('Nama sertifikat')->required()->maxLength(255),
                TextInput::make('icon')->label('Ikon (emoji)')->maxLength(16)->placeholder('🔒'),
                TextInput::make('score')->label('Nilai')->maxLength(20),
                TextInput::make('issuer')->label('Penerbit')->maxLength(255),
                TextInput::make('credential_url')->label('URL kredensial')->url()->maxLength(255)->columnSpanFull(),
                Toggle::make('is_visible')->label('Tampilkan di portfolio')->default(true),
            ]);
    }
}
