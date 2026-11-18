<?php

namespace App\Filament\Resources\SourceEndpointResource\Pages;

use App\Filament\Resources\SourceEndpointResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditSourceEndpoint extends EditRecord
{
    protected static string $resource = SourceEndpointResource::class;

    public function getTitle(): string
    {
        return 'Ubah Konfigurasi Endpoint';
    }

    public function getBreadcrumbs(): array
    {
        return [
            '/admin' => 'Dashboard',
            'Manajemen Sumber Data' => '#',
            '/admin/source-endpoints' => 'Endpoint & URL Target',
            'Ubah Data' => null,
        ];
    }

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make()->label('Hapus Endpoint'),
        ];
    }
}
