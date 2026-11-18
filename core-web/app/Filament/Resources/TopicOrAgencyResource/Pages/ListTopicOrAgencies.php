<?php

namespace App\Filament\Resources\TopicOrAgencyResource\Pages;

use App\Filament\Resources\TopicOrAgencyResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListTopicOrAgencies extends ListRecords
{
    protected static string $resource = TopicOrAgencyResource::class;

    public function getTitle(): string
    {
        return 'Direktori OPD & Kata Kunci Pemkab Jombang';
    }

    public function getSubheading(): ?string
    {
        return 'Pemetaan 10 Dinas Daerah Pemkab Jombang dan 4 Program Prioritas Nasional (PSN) untuk Analisis Sentimen NLP Berbasis Aspek.';
    }

    public function getBreadcrumbs(): array
    {
        return [
            '/admin' => 'Dashboard',
            'Konfigurasi Isu & OPD' => '#',
            'Daftar OPD & Kata Kunci' => null,
        ];
    }

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()
                ->label('Tambah OPD / Instansi Baru')
                ->icon('heroicon-m-plus-circle'),
        ];
    }
}
