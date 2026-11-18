<?php

namespace App\Filament\Resources\TopicOrAgencyResource\Pages;

use App\Filament\Resources\TopicOrAgencyResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateTopicOrAgency extends CreateRecord
{
    protected static string $resource = TopicOrAgencyResource::class;

    public function getTitle(): string
    {
        return 'Tambah OPD / Instansi Baru';
    }

    public function getBreadcrumbs(): array
    {
        return [
            '/admin' => 'Dashboard',
            'Konfigurasi Isu & OPD' => '#',
            '/admin/topic-or-agencies' => 'Daftar OPD & Kata Kunci',
            'Tambah Baru' => null,
        ];
    }
}
