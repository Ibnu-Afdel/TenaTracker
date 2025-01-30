<?php

namespace App\Livewire;

use App\Models\Challenge;
use App\Models\JournalEntry;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\WithPagination;

class ChallengeDetail extends Component
{
    use WithPagination;
    public $challenge;
    public $confirmingDelete = false;

    public $statusOptions = ['Ongoing', 'Completed', 'Paused'];

    protected $listeners = ['journalEntryCreated' => '$refresh', 'openJournalEntryForm'];

    public function mount($challengeId)
    {
        $this->challenge = Challenge::where('user_id', Auth::id())
            ->with('tags', 'journalEntries')
            ->findOrFail($challengeId);
    }

    public function toggleStatus()
    {
        if ($this->challenge->user_id === Auth::id()) {
            $currentStatus = $this->challenge->status;
            $newStatus = $currentStatus === 'Ongoing' ? 'Completed' : ($currentStatus === 'Completed' ? 'Paused' : 'Ongoing');
            $this->challenge->update(['status' => $newStatus]);
        }
    }


    public function deleteChallenge()
    {
        if ($this->challenge->user_id === Auth::id()) {
            $this->challenge->delete();
            return redirect()->route('dashboard');
        }
    }

    public function openJournalEntryForm()
{
    $this->dispatch('openJournalModal');
}



    public function render()
    {

        $journalEntries = JournalEntry::where('challenge_id', $this->challenge->id)
        ->latest()
        ->paginate(5);

        return view('livewire.challenge-detail', compact('journalEntries'))->layout('layouts.app');;
    }
}
