<?php

namespace App\Filament\Resources;

use App\Filament\Resources\RawContentResource\Pages;
use App\Models\RawContent;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class RawContentResource extends Resource
{
    protected static ?string $model = RawContent::class;

    protected static ?string $navigationIcon = 'heroicon-o-newspaper';

    protected static ?string $navigationGroup = 'Pemantauan & Data Mentah';

    protected static ?string $navigationLabel = 'Katalog Berita & Opini';

    protected static ?string $modelLabel = 'Berita / Konten';

    protected static ?string $pluralModelLabel = 'Katalog Berita & Opini';

    protected static ?string $breadcrumb = 'Katalog Berita & Opini';

    protected static ?int $navigationSort = 1;

    public static function getNavigationBadge(): ?string
    {
        return number_format(RawContent::count());
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Detail Konten Terkumpul')
                    ->schema([
                        Forms\Components\TextInput::make('title')
                            ->label('Judul Konten')
                            ->columnSpanFull(),
                        Forms\Components\TextInput::make('author')
                            ->label('Penulis / Akun'),
                        Forms\Components\TextInput::make('url')
                            ->label('Tautan Sumber')
                            ->url()
                            ->columnSpanFull(),
                        Forms\Components\DateTimePicker::make('published_at')
                            ->label('Tanggal Publikasi'),
                        Forms\Components\Select::make('scope')
                            ->label('Cakupan Kebijakan')
                            ->options([
                                'local_pemkab' => 'Isu Lokal Pemkab Jombang',
                                'national_strategic' => 'Program Strategis Nasional (PSN) Jombang',
                            ])
                            ->required(),
                        Forms\Components\Toggle::make('is_duplicate')
                            ->label('Berita Duplikat / Sindikasi'),
                        Forms\Components\Toggle::make('is_analyzed')
                            ->label('Sudah Dianalisis NLP'),
                        Forms\Components\Textarea::make('content_text')
                            ->label('Isi Konten Mentah')
                            ->rows(6)
                            ->columnSpanFull(),
                        Forms\Components\Textarea::make('clean_text')
                            ->label('Teks Ternormalisasi')
                            ->rows(4)
                            ->columnSpanFull(),
                    ])->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('title')
                    ->label('Judul Berita / Postingan')
                    ->limit(60)
                    ->searchable()
                    ->weight('medium'),
                Tables\Columns\TextColumn::make('endpoint.source.name')
                    ->label('Sumber Media')
                    ->badge()
                    ->sortable(),
                Tables\Columns\TextColumn::make('scope')
                    ->label('Cakupan')
                    ->badge()
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'national_strategic' => 'PSN Pusat (Jombang)',
                        default => 'Isu Lokal Pemkab',
                    })
                    ->color(fn (string $state): string => match ($state) {
                        'national_strategic' => 'info',
                        default => 'success',
                    })
                    ->sortable(),
                Tables\Columns\TextColumn::make('published_at')
                    ->label('Waktu Terbit')
                    ->dateTime('d M Y, H:i')
                    ->sortable(),
                Tables\Columns\IconColumn::make('is_duplicate')
                    ->label('Sindikasi')
                    ->boolean()
                    ->trueIcon('heroicon-o-document-duplicate')
                    ->falseIcon('heroicon-o-document-text'),
                Tables\Columns\IconColumn::make('is_analyzed')
                    ->label('Status NLP')
                    ->boolean(),
            ])
            ->defaultSort('published_at', 'desc')
            ->filters([
                Tables\Filters\SelectFilter::make('scope')
                    ->label('Cakupan Kebijakan')
                    ->options([
                        'local_pemkab' => 'Isu Lokal Pemkab Jombang',
                        'national_strategic' => 'Program Strategis Nasional (PSN) Jombang',
                    ]),
                Tables\Filters\TernaryFilter::make('is_duplicate')
                    ->label('Filter Duplikat / Sindikasi'),
                Tables\Filters\TernaryFilter::make('is_analyzed')
                    ->label('Filter Sudah Dianalisis'),
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
            'index' => Pages\ListRawContents::route('/'),
        ];
    }
}
