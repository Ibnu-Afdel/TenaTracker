<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\JournalEntry;
use Illuminate\Support\Facades\Auth;

class SharedJournalEntry extends Component
{
    public $entry;

    public function mount($shareToken)
    {
        $this->entry = JournalEntry::where('shared_link', $shareToken)->firstOrFail();
        
        // For private entries: require login AND ownership
        if ($this->entry->is_private) {
            if (!Auth::check()) {
                abort(403, 'Please login to view this private entry.');
            }
            
            if (Auth::id() !== $this->entry->user_id) {
                abort(403, 'You do not have permission to view this private entry.');
            }
        }}
        // Public entries are accessible to everyone - no check needed

    public function render()
    {
        return view('livewire.shared-journal-entry', [
            'journalEntry' => $this->entry,
            'isOwner' => Auth::check() && Auth::id() === $this->entry->user_id,
        ])->layout('layouts.guest');
    }
}

