<?php

namespace App\Filament\Resources\RawContentResource\Pages;

use App\Filament\Resources\RawContentResource;
use App\Models\RawContent;
use Filament\Actions;
use Filament\Resources\Components\Tab;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Database\Eloquent\Builder;

class ListRawContents extends ListRecords
{
    protected static string $resource = RawContentResource::class;

    public function getTitle(): string
    {
        return 'Katalog Berita & Percakapan Media';
    }

    public function getSubheading(): ?string
    {
        return 'Repository Terintegrasi Konten Berita Teranalisis NLP (Lokal Pemkab Jombang & Program Strategis Nasional).';
    }

    public function getBreadcrumbs(): array
    {
        return [
            '/admin' => 'Dashboard',
            'Pemantauan & Data Mentah' => '#',
            'Katalog Berita & Opini' => null,
        ];
    }

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()
                ->label('Input Konten Manual')
                ->icon('heroicon-m-plus'),
        ];
    }

    public function getTabs(): array
    {
        return [
            'all' => Tab::make('Semua Data Jombang')
                ->icon('heroicon-m-globe-alt')
                ->badge(RawContent::count()),

            'local_pemkab' => Tab::make('Isu Lokal Pemkab Jombang')
                ->icon('heroicon-m-building-office-2')
                ->badge(RawContent::where('scope', 'local_pemkab')->count())
                ->modifyQueryUsing(fn (Builder $query) => $query->where('scope', 'local_pemkab')),

            'national_strategic' => Tab::make('Program Strategis Nasional (PSN) Jombang')
                ->icon('heroicon-m-shield-check')
                ->badge(RawContent::where('scope', 'national_strategic')->count())
                ->modifyQueryUsing(fn (Builder $query) => $query->where('scope', 'national_strategic')),
        ];
    }
}
