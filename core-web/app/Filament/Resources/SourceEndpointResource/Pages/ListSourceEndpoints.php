<?php

namespace App\Filament\Resources\SourceEndpointResource\Pages;

use App\Filament\Resources\SourceEndpointResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListSourceEndpoints extends ListRecords
{
    protected static string $resource = SourceEndpointResource::class;

    public function getTitle(): string
    {
        return 'Endpoint & Target Pengambilan Data (Ingestion)';
    }

    public function getSubheading(): ?string
    {
        return 'URL Target RSS Feed, Scraper API, dan Konfigurasi Frekuensi Sinkronisasi Berita Pemkab Jombang.';
    }

    public function getBreadcrumbs(): array
    {
        return [
            '/admin' => 'Dashboard',
            'Manajemen Sumber Data' => '#',
            'Endpoint & URL Target' => null,
        ];
    }

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()
                ->label('Tambah Endpoint Baru')
                ->icon('heroicon-m-plus-circle'),
        ];
    }
}
