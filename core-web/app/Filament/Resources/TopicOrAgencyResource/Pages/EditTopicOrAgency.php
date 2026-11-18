<?php

namespace App\Filament\Resources\TopicOrAgencyResource\Pages;

use App\Filament\Resources\TopicOrAgencyResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditTopicOrAgency extends EditRecord
{
    protected static string $resource = TopicOrAgencyResource::class;

    public function getTitle(): string
    {
        return 'Ubah Data OPD / Instansi';
    }

    public function getBreadcrumbs(): array
    {
        return [
            '/admin' => 'Dashboard',
            'Konfigurasi Isu & OPD' => '#',
            '/admin/topic-or-agencies' => 'Daftar OPD & Kata Kunci',
            'Ubah Data' => null,
        ];
    }

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make()->label('Hapus OPD'),
        ];
    }
}
