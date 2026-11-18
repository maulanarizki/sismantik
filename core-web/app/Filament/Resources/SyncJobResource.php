<?php

namespace App\Filament\Resources;

use App\Filament\Resources\SyncJobResource\Pages;
use App\Models\SyncJob;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class SyncJobResource extends Resource
{
    protected static ?string $model = SyncJob::class;

    protected static ?string $navigationIcon = 'heroicon-o-clock';

    protected static ?string $navigationGroup = 'Pemantauan & Data Mentah';

    protected static ?string $navigationLabel = 'Riwayat Sinkronisasi';

    protected static ?string $modelLabel = 'Sesi Sinkronisasi';

    protected static ?string $pluralModelLabel = 'Riwayat Sinkronisasi';

    protected static ?string $breadcrumb = 'Riwayat Sinkronisasi';

    protected static ?int $navigationSort = 2;

    public static function getNavigationBadge(): ?string
    {
        return (string) \App\Models\SyncJob::count();
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Informasi Sesi Sinkronisasi')
                    ->schema([
                        Forms\Components\TextInput::make('job_uuid')
                            ->label('Job UUID')
                            ->disabled(),
                        Forms\Components\TextInput::make('source.name')
                            ->label('Sumber')
                            ->disabled(),
                        Forms\Components\TextInput::make('status')
                            ->label('Status')
                            ->disabled(),
                        Forms\Components\TextInput::make('triggered_by')
                            ->label('Pemicu')
                            ->disabled(),
                        Forms\Components\DatePicker::make('start_date')
                            ->label('Filter Dari Tanggal')
                            ->disabled(),
                        Forms\Components\DatePicker::make('end_date')
                            ->label('Filter Sampai Tanggal')
                            ->disabled(),
                        Forms\Components\TextInput::make('items_scraped')
                            ->label('Data Terambil')
                            ->disabled(),
                        Forms\Components\TextInput::make('items_processed')
                            ->label('Data Diproses')
                            ->disabled(),
                        Forms\Components\Textarea::make('log_messages')
                            ->label('Log Aktivitas')
                            ->rows(6)
                            ->columnSpanFull()
                            ->disabled(),
                    ])->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Waktu Eksekusi')
                    ->dateTime('d M Y, H:i:s')
                    ->sortable(),
                Tables\Columns\TextColumn::make('source.name')
                    ->label('Sumber Target')
                    ->searchable(),
                Tables\Columns\TextColumn::make('endpoint.name')
                    ->label('Endpoint Target')
                    ->placeholder('Semua Endpoint'),
                Tables\Columns\BadgeColumn::make('status')
                    ->label('Status')
                    ->colors([
                        'warning' => 'pending',
                        'primary' => 'running',
                        'success' => 'completed',
                        'danger' => 'failed',
                    ]),
                Tables\Columns\TextColumn::make('items_scraped')
                    ->label('Terambil')
                    ->numeric()
                    ->alignCenter(),
                Tables\Columns\TextColumn::make('items_processed')
                    ->label('Diproses')
                    ->numeric()
                    ->alignCenter(),
                Tables\Columns\TextColumn::make('triggered_by')
                    ->label('Pemicu')
                    ->badge()
                    ->color('secondary'),
            ])
            ->defaultSort('created_at', 'desc')
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->options([
                        'pending' => 'Pending',
                        'running' => 'Running',
                        'completed' => 'Completed',
                        'failed' => 'Failed',
                    ]),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
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
            'index' => Pages\ListSyncJobs::route('/'),
        ];
    }
}
