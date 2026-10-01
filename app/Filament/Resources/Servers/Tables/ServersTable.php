<?php

namespace App\Filament\Resources\Servers\Tables;

use App\Filament\Resources\Servers\ServerResource;
use App\Models\Server;
use Filament\Actions\Action;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class ServersTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->defaultSort('sort_order', 'asc')
            ->columns([
                TextColumn::make('sort_order')
                    ->label('Urutan')
                    ->sortable(),

                TextColumn::make('name')
                    ->label('Nama Server')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('key')
                    ->label('Key')
                    ->badge()
                    ->searchable(),

                TextColumn::make('type')
                    ->label('Tipe')
                    ->badge()
                    ->color(fn(string $state): string => match ($state) {
                        'movie' => 'info',
                        'tv' => 'warning',
                        'both' => 'success',
                        default => 'gray',
                    }),

                IconColumn::make('is_active')
                    ->label('Aktif')
                    ->boolean(),
            ])
            ->actions([
                // 🔼 Tombol Naik
                Action::make('move_up')
                    ->label('')
                    ->tooltip('Pindah ke Atas')
                    ->icon('heroicon-o-chevron-up')
                    ->color('gray')
                    ->action(function (Server $record) {
                        $previousServer = Server::where('sort_order', '<', $record->sort_order)
                            ->orderBy('sort_order', 'desc')
                            ->first();

                        if ($previousServer) {
                            $currentOrder = $record->sort_order;
                            $record->update(['sort_order' => $previousServer->sort_order]);
                            $previousServer->update(['sort_order' => $currentOrder]);
                        }
                    }),

                // 🔽 Tombol Turun
                Action::make('move_down')
                    ->label('')
                    ->tooltip('Pindah ke Bawah')
                    ->icon('heroicon-o-chevron-down')
                    ->color('gray')
                    ->action(function (Server $record) {
                        $nextServer = Server::where('sort_order', '>', $record->sort_order)
                            ->orderBy('sort_order', 'asc')
                            ->first();

                        if ($nextServer) {
                            $currentOrder = $record->sort_order;
                            $record->update(['sort_order' => $nextServer->sort_order]);
                            $nextServer->update(['sort_order' => $currentOrder]);
                        }
                    }),

                Action::make('edit_manual')
                    ->label('Edit')
                    ->icon('heroicon-o-pencil-square')
                    ->color('warning')
                    ->url(fn($record) => ServerResource::getUrl('edit', ['record' => $record])),

                Action::make('delete_manual')
                    ->label('Hapus')
                    ->icon('heroicon-o-trash')
                    ->color('danger')
                    ->requiresConfirmation()
                    ->action(fn($record) => $record->delete()),
            ]);
    }
}
