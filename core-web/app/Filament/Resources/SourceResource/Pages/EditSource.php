<?php

namespace App\Filament\Resources\SourceResource\Pages;

use App\Filament\Resources\SourceResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditSource extends EditRecord
{
    protected static string $resource = SourceResource::class;

    public function getTitle(): string
    {
        return 'Ubah Sumber Media';
    }

    public function getBreadcrumbs(): array
    {
        return [
            '/admin' => 'Dashboard',
            'Manajemen Sumber Data' => '#',
            '/admin/sources' => 'Portal & Akun Medsos',
            'Ubah Data' => null,
        ];
    }

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make()->label('Hapus Sumber'),
        ];
    }
}
