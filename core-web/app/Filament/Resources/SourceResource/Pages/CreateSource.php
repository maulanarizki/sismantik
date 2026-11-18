<?php

namespace App\Filament\Resources\SourceResource\Pages;

use App\Filament\Resources\SourceResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateSource extends CreateRecord
{
    protected static string $resource = SourceResource::class;

    public function getTitle(): string
    {
        return 'Tambah Sumber Media Baru';
    }

    public function getBreadcrumbs(): array
    {
        return [
            '/admin' => 'Dashboard',
            'Manajemen Sumber Data' => '#',
            '/admin/sources' => 'Portal & Akun Medsos',
            'Tambah Baru' => null,
        ];
    }
}
