<?php

namespace App\Filament\Resources\SourceEndpointResource\Pages;

use App\Filament\Resources\SourceEndpointResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateSourceEndpoint extends CreateRecord
{
    protected static string $resource = SourceEndpointResource::class;

    public function getTitle(): string
    {
        return 'Tambah Endpoint & URL Target Baru';
    }

    public function getBreadcrumbs(): array
    {
        return [
            '/admin' => 'Dashboard',
            'Manajemen Sumber Data' => '#',
            '/admin/source-endpoints' => 'Endpoint & URL Target',
            'Tambah Baru' => null,
        ];
    }
}
