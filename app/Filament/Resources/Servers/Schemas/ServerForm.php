<?php

namespace App\Filament\Resources\Servers\Schemas;

use App\Models\Server;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class ServerForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->label('Nama Server')
                    ->placeholder('Contoh: VidStuck Pro')
                    ->required(),

                TextInput::make('key')
                    ->label('Server Key')
                    ->placeholder('vidstuck')
                    ->required(),

                Select::make('type')
                    ->label('Tipe Media')
                    ->options([
                        'movie' => 'Movie Only',
                        'tv' => 'TV Series Only',
                        'both' => 'Movie & TV Series',
                    ])
                    ->default('both')
                    ->required(),

                TextInput::make('movie_url_pattern')
                    ->label('Pattern URL Movie')
                    ->placeholder('https://vidstuck.xyz/embed/movie/{tmdb_id}?progress={start}')
                    ->columnSpanFull(),

                TextInput::make('tv_url_pattern')
                    ->label('Pattern URL TV Series')
                    ->placeholder('https://vidstuck.xyz/embed/tv/{tmdb_id}/{season}/{episode}?progress={start}')
                    ->columnSpanFull(),

                Toggle::make('is_active')
                    ->label('Status Aktif')
                    ->default(true),

                TextInput::make('sort_order')
                    ->label('Urutan')
                    ->numeric()
                    ->default(fn() => (Server::max('sort_order') ?? 0) + 1) // ✅ Otomatis increment (+1 dari max)
                    ->required(),
            ]);
    }
}
