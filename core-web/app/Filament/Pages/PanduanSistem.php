<?php

namespace App\Filament\Pages;

use Filament\Actions\Action;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Illuminate\Support\Facades\Artisan;

class PanduanSistem extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-book-open';

    protected static ?string $navigationGroup = 'Dokumentasi & SOP';

    protected static ?string $navigationLabel = 'Panduan & Prosedur Sistem';

    protected static ?string $title = 'Panduan Penggunaan & Prosedur Operasional SISMANTIK';

    protected static ?int $navigationSort = 10;

    protected static string $view = 'filament.pages.panduan-sistem';

    public function getBreadcrumbs(): array
    {
        return [
            '/admin' => 'Dashboard',
            'Dokumentasi & SOP' => '#',
            'Panduan & Prosedur Sistem' => null,
        ];
    }

    protected function getHeaderActions(): array
    {
        return [
            Action::make('trigger_replace_total')
                ->label('Eksekusi Replace Data Total')
                ->icon('heroicon-m-arrow-path-rounded-square')
                ->color('danger')
                ->modalHeading('Konfirmasi Replace Data Total (Clean Re-Ingestion)')
                ->modalDescription('PERHATIAN: Tindakan ini akan mengosongkan seluruh histori percakapan dan analisis sentimen, lalu menarik data segar 12 bulan terakhir (maksimal 1.000 data/bulan).')
                ->requiresConfirmation()
                ->modalSubmitActionLabel('Ya, Bersihkan & Tarik Ulang')
                ->action(function () {
                    try {
                        Artisan::call('sismantik:replace-total', [
                            '--months' => 12,
                            '--max-per-month' => 1000,
                            '--confirm' => 'yes',
                        ]);

                        Notification::make()
                            ->title('Replace Data Total Berhasil')
                            ->body('Seluruh database telah dibersihkan dan diisi ulang dengan data segar 12 bulan.')
                            ->success()
                            ->send();
                    } catch (\Throwable $e) {
                        Notification::make()
                            ->title('Gagal Replace Data')
                            ->body($e->getMessage())
                            ->danger()
                            ->send();
                    }
                }),
        ];
    }
}

