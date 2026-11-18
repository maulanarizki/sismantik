<?php

namespace App\Filament\Resources\RawContentResource\Pages;

use App\Filament\Resources\RawContentResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditRawContent extends EditRecord
{
    protected static string $resource = RawContentResource::class;

    public function getTitle(): string
    {
        return 'Lihat & Edit Detail Konten';
    }

    public function getBreadcrumbs(): array
    {
        return [
            '/admin' => 'Dashboard',
            'Pemantauan & Data Mentah' => '#',
            '/admin/raw-contents' => 'Katalog Berita & Opini',
            'Detail Konten' => null,
        ];
    }

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make()->label('Hapus Konten'),
        ];
    }
}
