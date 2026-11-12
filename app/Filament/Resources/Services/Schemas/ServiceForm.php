<?php

namespace App\Filament\Resources\Services\Schemas;

use App\Models\Service;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TagsInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class ServiceForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->columns(2)
            ->components([
                TextInput::make('title')->label('Nama layanan')->required()->maxLength(255),
                Select::make('icon')->label('Ikon')->options(Service::ICONS)->required()->default('code')->native(false),
                Textarea::make('description')->label('Deskripsi')->rows(3)->maxLength(1000)->columnSpanFull(),
                TagsInput::make('features')
                    ->label('Poin layanan')
                    ->placeholder('Ketik lalu Enter')
                    ->helperText('Tampil sebagai daftar poin di kartu layanan. Disarankan 3-5 poin.')
                    ->nestedRecursiveRules(['max:120'])
                    ->columnSpanFull(),
                Toggle::make('is_visible')->label('Tampilkan di website')->default(true),
            ]);
    }
}
