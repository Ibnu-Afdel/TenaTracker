<?php

namespace App\Filament\Journal\Resources\JournalEntryResource\Pages;

use App\Filament\Journal\Resources\JournalEntryResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Database\Eloquent\Model;

class EditJournalEntry extends EditRecord
{
    protected static string $resource = JournalEntryResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }

    protected function mutateFormDataBeforeSave(array $data): array
    {
        // If privacy status changed to public and there's no shared link, generate one
        if (!($data['is_private'] ?? true) && empty($this->record->shared_link)) {
            $data['shared_link'] = (string) \Illuminate\Support\Str::uuid();
        }
        
        return $data;
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}

