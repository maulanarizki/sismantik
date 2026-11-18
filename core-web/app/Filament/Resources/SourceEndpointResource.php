<?php

namespace App\Filament\Resources;

use App\Filament\Resources\SourceEndpointResource\Pages;
use App\Models\SourceEndpoint;
use App\Services\IngestionService;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class SourceEndpointResource extends Resource
{
    protected static ?string $model = SourceEndpoint::class;

    protected static ?string $navigationIcon = 'heroicon-o-link';

    protected static ?string $navigationGroup = 'Manajemen Sumber Data';

    protected static ?string $navigationLabel = 'Endpoint & URL Target';

    protected static ?string $modelLabel = 'Target Endpoint';

    protected static ?string $pluralModelLabel = 'Endpoint & URL Target';

    protected static ?string $breadcrumb = 'Endpoint & URL Target';

    protected static ?int $navigationSort = 2;

    public static function getNavigationBadge(): ?string
    {
        return (string) \App\Models\SourceEndpoint::count();
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Konfigurasi Endpoint & Target Scraping')
                    ->description('Tentukan URL spesifik, parser yang digunakan, dan pembatas konten')
                    ->schema([
                        Forms\Components\Select::make('source_id')
                            ->label('Sumber Induk')
                            ->relationship('source', 'name')
                            ->searchable()
                            ->preload()
                            ->required(),
                        Forms\Components\TextInput::make('name')
                            ->label('Nama Endpoint')
                            ->placeholder('Contoh: Feed Berita Regional / Postingan Humas')
                            ->required()
                            ->maxLength(255),
                        Forms\Components\TextInput::make('endpoint_url')
                            ->label('URL Endpoint Target')
                            ->placeholder('https://www.antaranews.com/rss/terkini.xml')
                            ->required()
                            ->columnSpanFull(),
                        Forms\Components\Select::make('parser_type')
                            ->label('Metode Parser')
                            ->options([
                                'rss' => 'RSS / Atom XML Feed',
                                'auto_trafilatura' => 'Trafilatura (Auto Article Extractor)',
                                'playwright_custom' => 'Playwright Headless (Render JS Custom)',
                                'social_profile' => 'Social Media Public Scraper',
                            ])
                            ->default('auto_trafilatura')
                            ->required(),
                        Forms\Components\TextInput::make('default_max_items')
                            ->label('Batas Maksimal Data Default')
                            ->numeric()
                            ->default(50)
                            ->required(),
                        Forms\Components\Toggle::make('is_active')
                            ->label('Status Aktif')
                            ->default(true),
                        Forms\Components\KeyValue::make('selectors_config')
                            ->label('Konfigurasi Selector Kustom (Opsional)')
                            ->helperText('Hanya jika menggunakan Playwright / Custom CSS: title, content, date, author')
                            ->keyLabel('Elemen')
                            ->valueLabel('CSS Selector / XPath')
                            ->columnSpanFull(),
                    ])->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('source.name')
                    ->label('Sumber Induk')
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('name')
                    ->label('Nama Endpoint')
                    ->searchable()
                    ->weight('medium'),
                Tables\Columns\BadgeColumn::make('parser_type')
                    ->label('Tipe Parser')
                    ->colors([
                        'primary' => 'rss',
                        'success' => 'auto_trafilatura',
                        'warning' => 'playwright_custom',
                        'danger' => 'social_profile',
                    ])
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'rss' => 'RSS Feed',
                        'auto_trafilatura' => 'Trafilatura',
                        'playwright_custom' => 'Playwright JS',
                        'social_profile' => 'Social Media',
                        default => $state,
                    }),
                Tables\Columns\TextColumn::make('default_max_items')
                    ->label('Limit')
                    ->numeric()
                    ->alignCenter(),
                Tables\Columns\ToggleColumn::make('is_active')
                    ->label('Status On/Off'),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('source_id')
                    ->label('Filter Sumber')
                    ->relationship('source', 'name'),
                Tables\Filters\SelectFilter::make('parser_type')
                    ->label('Filter Tipe Parser')
                    ->options([
                        'rss' => 'RSS Feed',
                        'auto_trafilatura' => 'Trafilatura',
                        'playwright_custom' => 'Playwright JS',
                        'social_profile' => 'Social Media',
                    ]),
            ])
            ->actions([
                Tables\Actions\Action::make('sync_now')
                    ->label('Sinkron Sekarang')
                    ->icon('heroicon-o-arrow-path')
                    ->color('success')
                    ->form([
                        Forms\Components\DatePicker::make('start_date')
                            ->label('Dari Tanggal')
                            ->default(now()->subDays(7)),
                        Forms\Components\DatePicker::make('end_date')
                            ->label('Sampai Tanggal')
                            ->default(now()),
                        Forms\Components\TextInput::make('max_items')
                            ->label('Jumlah Maksimal Data')
                            ->numeric()
                            ->default(fn (SourceEndpoint $record) => $record->default_max_items ?? 30)
                            ->required(),
                    ])
                    ->action(function (SourceEndpoint $record, array $data, IngestionService $service) {
                        $syncJob = $service->syncEndpoint(
                            $record,
                            $data['start_date'] ?? null,
                            $data['end_date'] ?? null,
                            (int) ($data['max_items'] ?? 30)
                        );

                        if ($syncJob->status === 'completed') {
                            Notification::make()
                                ->title('Sinkronisasi Selesai')
                                ->body("Berhasil mengambil {$syncJob->items_scraped} konten dari {$record->name}.")
                                ->success()
                                ->send();
                        } else {
                            Notification::make()
                                ->title('Sinkronisasi Gagal')
                                ->body($syncJob->log_messages)
                                ->danger()
                                ->send();
                        }
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
            'index' => Pages\ListSourceEndpoints::route('/'),
            'create' => Pages\CreateSourceEndpoint::route('/create'),
            'edit' => Pages\EditSourceEndpoint::route('/{record}/edit'),
        ];
    }
}
