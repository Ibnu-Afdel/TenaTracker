<?php

namespace App\Filament\Journal\Resources\JournalEntryResource\Pages;

use App\Filament\Journal\Resources\JournalEntryResource;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Database\Eloquent\Model;

class CreateJournalEntry extends CreateRecord
{
    protected static string $resource = JournalEntryResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['user_id'] = auth()->id();
        
        // Generate a shared link if not private
        if (!($data['is_private'] ?? false)) {
            $data['shared_link'] = (string) \Illuminate\Support\Str::uuid();
        }
        
        return $data;
    }
}

