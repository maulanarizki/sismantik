<?php

namespace App\Filament\Resources;

use App\Filament\Resources\TopicOrAgencyResource\Pages;
use App\Models\TopicOrAgency;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class TopicOrAgencyResource extends Resource
{
    protected static ?string $model = TopicOrAgency::class;

    protected static ?string $navigationIcon = 'heroicon-o-building-office-2';

    protected static ?string $navigationGroup = 'Konfigurasi Isu & OPD';

    protected static ?string $navigationLabel = 'Daftar OPD & Kata Kunci';

    protected static ?string $modelLabel = 'OPD / Instansi';

    protected static ?string $pluralModelLabel = 'Daftar OPD & Kata Kunci';

    protected static ?string $breadcrumb = 'Daftar OPD & Kata Kunci';

    protected static ?int $navigationSort = 1;

    public static function getNavigationBadge(): ?string
    {
        return (string) TopicOrAgency::count();
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Organisasi Perangkat Daerah (OPD) & Isu Terkait')
                    ->description('Daftar dinas dan kata kunci yang dipetakan pada Aspect-Based Sentiment Analysis')
                    ->schema([
                        Forms\Components\TextInput::make('name')
                            ->label('Nama Dinas / OPD')
                            ->placeholder('Contoh: Dinas Pekerjaan Umum & Penataan Ruang')
                            ->required()
                            ->maxLength(255),
                        Forms\Components\TextInput::make('code')
                            ->label('Kode Singkat')
                            ->placeholder('dinas_pupr')
                            ->required()
                            ->unique(ignoreRecord: true)
                            ->maxLength(50),
                        Forms\Components\TextInput::make('category')
                            ->label('Kategori Sektor')
                            ->placeholder('Infrastruktur / Kesehatan / Pendidikan')
                            ->maxLength(100),
                        Forms\Components\Toggle::make('is_active')
                            ->label('Aktifkan Pemantauan')
                            ->default(true),
                        Forms\Components\TagsInput::make('keywords')
                            ->label('Kata Kunci Terkait (Keywords)')
                            ->placeholder('Ketik kata kunci dan tekan Enter (misal: jalan rusak, aspal, jembatan)')
                            ->required()
                            ->columnSpanFull(),
                        Forms\Components\TagsInput::make('aliases')
                            ->label('Nama Alias / Singkatan')
                            ->placeholder('PUPR, Bina Marga')
                            ->columnSpanFull(),
                    ])->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label('Nama Dinas / Instansi')
                    ->searchable()
                    ->weight('bold'),
                Tables\Columns\TextColumn::make('category')
                    ->label('Kategori')
                    ->badge()
                    ->color('info')
                    ->sortable(),
                Tables\Columns\TagsColumn::make('keywords')
                    ->label('Kata Kunci')
                    ->limit(4),
                Tables\Columns\ToggleColumn::make('is_active')
                    ->label('Status On/Off'),
            ])
            ->filters([
                Tables\Filters\TernaryFilter::make('is_active')
                    ->label('Status Aktif'),
            ])
            ->actions([
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
            'index' => Pages\ListTopicOrAgencies::route('/'),
            'create' => Pages\CreateTopicOrAgency::route('/create'),
            'edit' => Pages\EditTopicOrAgency::route('/{record}/edit'),
        ];
    }
}
