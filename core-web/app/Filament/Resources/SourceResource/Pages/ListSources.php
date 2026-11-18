<?php

namespace App\Filament\Resources\SourceResource\Pages;

use App\Filament\Resources\SourceResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListSources extends ListRecords
{
    protected static string $resource = SourceResource::class;

    public function getTitle(): string
    {
        return 'Direktori Portal Berita & Akun Media Sosial';
    }

    public function getSubheading(): ?string
    {
        return 'Daftar Portal Media Online, RSS Feed, dan Kanal Media Sosial Resmi yang Terhubung dengan SISMANTIK.';
    }

    public function getBreadcrumbs(): array
    {
        return [
            '/admin' => 'Dashboard',
            'Manajemen Sumber Data' => '#',
            'Portal & Akun Medsos' => null,
        ];
    }

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()
                ->label('Tambah Sumber Media Baru')
                ->icon('heroicon-m-plus-circle'),
        ];
    }
}
