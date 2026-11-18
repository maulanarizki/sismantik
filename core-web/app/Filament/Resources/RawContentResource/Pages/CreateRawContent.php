<?php

namespace App\Filament\Resources\RawContentResource\Pages;

use App\Filament\Resources\RawContentResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateRawContent extends CreateRecord
{
    protected static string $resource = RawContentResource::class;

    public function getTitle(): string
    {
        return 'Input Konten Berita Manual';
    }

    public function getBreadcrumbs(): array
    {
        return [
            '/admin' => 'Dashboard',
            'Pemantauan & Data Mentah' => '#',
            '/admin/raw-contents' => 'Katalog Berita & Opini',
            'Input Manual' => null,
        ];
    }
}
