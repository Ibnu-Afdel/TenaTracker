<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\JournalEntry as JournalEntryModel;
use Illuminate\Support\Carbon;

class SharedJournalEntry extends Component
{
    public JournalEntryModel $entry;

    public function mount($shareToken)
    {
        $this->entry = JournalEntryModel::where('shared_link', $shareToken)->firstOrFail();
    }

    public function render()
    {
        return view('livewire.shared-journal-entry')
            ->layout('layouts.app');
    }
}

