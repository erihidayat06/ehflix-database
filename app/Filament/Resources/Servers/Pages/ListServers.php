<?php

namespace App\Filament\Resources\Servers\Pages;

use App\Filament\Resources\Servers\ServerResource;
use Filament\Actions\Action;
use Filament\Resources\Pages\ListRecords;

class ListServers extends ListRecords
{
    protected static string $resource = ServerResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Action::make('test')
                ->label('+ Tambah Server')
                ->icon('heroicon-o-plus')
                ->url(ServerResource::getUrl('create')),
        ];
    }
}
