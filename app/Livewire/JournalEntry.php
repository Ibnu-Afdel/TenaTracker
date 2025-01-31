<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\JournalEntry as JournalEntryModel;
use Illuminate\Support\Str;

class JournalEntry extends Component
{
    public $entry;
    public $isEditing = false;
    public $content;
    
    public function mount(JournalEntryModel $entry)
    {
        $this->entry = $entry;
        $this->content = $entry->content;
    }

    public function toggleEdit()
    {
        $this->isEditing = !$this->isEditing;
    }
    
    public function save()
    {
        $this->entry->update([
            'content' => $this->content
        ]);
        $this->isEditing = false;
    }

    public function getShareLink()
    {
        return route('shared.journal', ['shareToken' => $this->entry->shared_link]);
    }


    public function render()
    {
        return view('livewire.journal-entry');
    }
}

