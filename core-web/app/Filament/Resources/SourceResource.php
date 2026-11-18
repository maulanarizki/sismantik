<?php

namespace App\Filament\Resources;

use App\Filament\Resources\SourceResource\Pages;
use App\Models\Source;
use App\Services\IngestionService;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class SourceResource extends Resource
{
    protected static ?string $model = Source::class;

    protected static ?string $navigationIcon = 'heroicon-o-globe-alt';

    protected static ?string $navigationGroup = 'Manajemen Sumber Data';

    protected static ?string $navigationLabel = 'Portal & Akun Medsos';

    protected static ?string $modelLabel = 'Sumber Media';

    protected static ?string $pluralModelLabel = 'Portal & Akun Medsos';

    protected static ?string $breadcrumb = 'Portal & Akun Medsos';

    protected static ?int $navigationSort = 1;

    public static function getNavigationBadge(): ?string
    {
        return (string) Source::count();
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Informasi Sumber Data')
                    ->description('Kelola media portal berita atau akun media sosial yang dipantau')
                    ->schema([
                        Forms\Components\TextInput::make('name')
                            ->label('Nama Sumber')
                            ->placeholder('Contoh: Antara News Jatim / Humas Pemda IG')
                            ->required()
                            ->maxLength(255),
                        Forms\Components\TextInput::make('code')
                            ->label('Kode Unik')
                            ->placeholder('antara_jatim')
                            ->required()
                            ->unique(ignoreRecord: true)
                            ->maxLength(100),
                        Forms\Components\Select::make('platform_type')
                            ->label('Tipe Platform')
                            ->options([
                                'web_rss' => 'Portal Berita (RSS Feed)',
                                'web_html' => 'Portal Berita (Web Scraper / HTML)',
                                'facebook' => 'Facebook (Halaman / Grup Publik)',
                                'instagram' => 'Instagram (Akun Publik / Komentar)',
                                'tiktok' => 'TikTok (Akun / Tagar)',
                                'x' => 'X / Twitter (Pencarian Kata Kunci)',
                            ])
                            ->required(),
                        Forms\Components\Select::make('tier')
                            ->label('Hirarki Media (Tier)')
                            ->options([
                                'tier_1' => 'Tier 1 - Nasional Mainstream (Kompas/Detik/Antara)',
                                'tier_2' => 'Tier 2 - Regional & Lokal (Jatim/Daerah)',
                                'tier_3' => 'Tier 3 - Internasional (BBC/Reuters/Bloomberg)',
                                'tier_4' => 'Tier 4 - Media Sosial & Komunitas Warga',
                            ])
                            ->default('tier_2')
                            ->required(),
                        Forms\Components\TextInput::make('authority_score')
                            ->label('Skor Otoritas Media (0.00 s.d. 1.00)')
                            ->numeric()
                            ->default(0.85)
                            ->step(0.05)
                            ->required(),
                        Forms\Components\TextInput::make('base_url')
                            ->label('Base URL / Tautan Utama')
                            ->url()
                            ->placeholder('https://www.antaranews.com')
                            ->required()
                            ->maxLength(500),
                        Forms\Components\TextInput::make('sync_interval_minutes')
                            ->label('Interval Sinkronisasi (Menit)')
                            ->numeric()
                            ->default(60)
                            ->required(),
                        Forms\Components\Toggle::make('is_active')
                            ->label('Status Aktif')
                            ->default(true),
                        Forms\Components\Textarea::make('description')
                            ->label('Deskripsi / Catatan')
                            ->columnSpanFull(),
                    ])->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label('Nama Sumber')
                    ->searchable()
                    ->sortable()
                    ->weight('bold'),
                Tables\Columns\BadgeColumn::make('tier')
                    ->label('Hirarki Media')
                    ->colors([
                        'danger' => 'tier_1',
                        'warning' => 'tier_2',
                        'info' => 'tier_3',
                        'secondary' => 'tier_4',
                    ])
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'tier_1' => 'Tier 1 (Nasional)',
                        'tier_2' => 'Tier 2 (Regional)',
                        'tier_3' => 'Tier 3 (Global)',
                        'tier_4' => 'Tier 4 (Medsos)',
                        default => $state,
                    })
                    ->sortable(),
                Tables\Columns\BadgeColumn::make('platform_type')
                    ->label('Platform')
                    ->colors([
                        'primary' => 'web_rss',
                        'info' => 'web_html',
                        'sky' => 'x',
                        'warning' => 'instagram',
                        'success' => 'facebook',
                        'danger' => 'tiktok',
                    ])
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'web_rss' => 'Web RSS',
                        'web_html' => 'Web HTML',
                        'facebook' => 'Facebook',
                        'instagram' => 'Instagram',
                        'tiktok' => 'TikTok',
                        'x' => 'X (Twitter)',
                        default => $state,
                    }),
                Tables\Columns\TextColumn::make('endpoints_count')
                    ->label('Total Endpoint')
                    ->counts('endpoints')
                    ->badge(),
                Tables\Columns\ToggleColumn::make('is_active')
                    ->label('Status On/Off'),
                Tables\Columns\TextColumn::make('last_synced_at')
                    ->label('Terakhir Sinkron')
                    ->dateTime('d M Y, H:i')
                    ->placeholder('Belum pernah')
                    ->sortable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('tier')
                    ->label('Filter Hirarki Media')
                    ->options([
                        'tier_1' => 'Tier 1 - Nasional Mainstream',
                        'tier_2' => 'Tier 2 - Regional & Lokal',
                        'tier_3' => 'Tier 3 - Internasional',
                        'tier_4' => 'Tier 4 - Media Sosial',
                    ]),
                Tables\Filters\SelectFilter::make('platform_type')
                    ->label('Filter Platform')
                    ->options([
                        'web_rss' => 'Web RSS',
                        'web_html' => 'Web HTML',
                        'facebook' => 'Facebook',
                        'instagram' => 'Instagram',
                        'tiktok' => 'TikTok',
                        'x' => 'X',
                    ]),
                Tables\Filters\TernaryFilter::make('is_active')
                    ->label('Status Aktif'),
            ])
            ->actions([
                Tables\Actions\Action::make('sync_now')
                    ->label('Sinkron Sekarang')
                    ->icon('heroicon-o-arrow-path')
                    ->color('success')
                    ->form([
                        Forms\Components\DatePicker::make('start_date')
                            ->label('Dari Tanggal (Start Date)')
                            ->default(now()->subDays(7)),
                        Forms\Components\DatePicker::make('end_date')
                            ->label('Sampai Tanggal (End Date)')
                            ->default(now()),
                        Forms\Components\TextInput::make('max_items')
                            ->label('Batas Maksimal Data (Items)')
                            ->numeric()
                            ->default(30)
                            ->required(),
                    ])
                    ->action(function (Source $record, array $data, IngestionService $service) {
                        $jobs = $service->syncSource(
                            $record,
                            $data['start_date'] ?? null,
                            $data['end_date'] ?? null,
                            (int) ($data['max_items'] ?? 30)
                        );

                        Notification::make()
                            ->title('Sinkronisasi Berhasil Dijalankan')
                            ->body("Berhasil mengeksekusi sinkronisasi untuk {$record->name}.")
                            ->success()
                            ->send();
                    }),
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListSources::route('/'),
            'create' => Pages\CreateSource::route('/create'),
            'edit' => Pages\EditSource::route('/{record}/edit'),
        ];
    }
}
