<?php

namespace App\Filament\Resources\SyncJobResource\Pages;

use App\Filament\Resources\SyncJobResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListSyncJobs extends ListRecords
{
    protected static string $resource = SyncJobResource::class;

    public function getTitle(): string
    {
        return 'Audit Log & Riwayat Sinkronisasi Berita';
    }

    public function getSubheading(): ?string
    {
        return 'Rekam Jejak Eksekusi Job Ingestion Data Berita, Jumlah Data Ditarik, dan Waktu Eksekusi.';
    }

    public function getBreadcrumbs(): array
    {
        return [
            '/admin' => 'Dashboard',
            'Pemantauan & Data Mentah' => '#',
            'Riwayat Sinkronisasi' => null,
        ];
    }

    protected function getHeaderActions(): array
    {
        return [];
    }
}
