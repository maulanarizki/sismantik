<?php

namespace App\Filament\Resources\SyncJobResource\Pages;

use App\Filament\Resources\SyncJobResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditSyncJob extends EditRecord
{
    protected static string $resource = SyncJobResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
