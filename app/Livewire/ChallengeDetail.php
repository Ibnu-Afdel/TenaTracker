<?php

namespace App\Livewire;

use App\Models\Challenge;
use App\Models\JournalEntry;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Livewire\Component;
use Livewire\WithPagination;

class ChallengeDetail extends Component
{
    use WithPagination;
    public $challenge;
    public $confirmingDelete = false;
    public $shareToken = null;
    public $favorite = false;
    public $search = '';
    public $privacyFilter = 'all';
    public $editingJournalId = null;
    public $showDeleteJournalModal = false;
    public $journalToDelete = null;
    
    public $statusOptions = ['Ongoing', 'Completed', 'Paused'];

    protected $listeners = [
        'journalEntryCreated' => '$refresh',
        'journalEntryUpdated' => '$refresh'
    ];

    public function mount($challengeId = null, $shareToken = null)
    {
        if ($shareToken) {
            $this->shareToken = $shareToken;
            $this->challenge = Challenge::where('sharing_token', $shareToken)
                ->with(['tags', 'journalEntries' => function ($query) {
                    $query->with('tags');
                }])
                ->firstOrFail();
        } elseif ($challengeId) {
            $this->challenge = Challenge::where('user_id', Auth::id())
                ->with(['tags', 'journalEntries' => function ($query) {
                    $query->with('tags');
                }])
                ->findOrFail($challengeId);
        }
        
        if ($this->challenge) {
            $this->favorite = (bool) $this->challenge->favorite;
        }
    }
    public function toggleStatus()
    {
        if ($this->challenge->user_id === Auth::id()) {
            $newStatus = match ($this->challenge->status) {
                'Ongoing' => 'Completed',
                'Completed' => 'Paused',
                default => 'Ongoing'
            };

            $this->challenge->update(['status' => $newStatus]);
        }
    }

    public function confirmDeleteChallenge()
    {
        $this->confirmingDelete = true;
    }

    public function cancelDeleteChallenge()
    {
        $this->confirmingDelete = false;
    }
    
    public function deleteChallenge()
    {
        if ($this->challenge->user_id === Auth::id()) {
            $this->challenge->delete();
            return redirect()->route('dashboard');
        }
    }

    public function generateShareToken()
    {
        Log::debug('Generating share token', [
            'challenge_id' => $this->challenge->id,
            'existing_token' => $this->challenge->sharing_token
        ]);
        
        if (!$this->challenge->sharing_token) {
            $this->challenge->sharing_token = Str::random(32);
            $this->challenge->save();
            Log::debug('Generated new token', [
                'new_token' => $this->challenge->sharing_token
            ]);
        }
        return $this->challenge->sharing_token;
    }

    public function getShareLink()
    {
        $shareToken = $this->challenge->sharing_token ?? $this->generateShareToken();
        Log::debug('Generating share link', [
            'sharing_token' => $shareToken,
            'challenge_id' => $this->challenge->id
        ]);
        
        $url = route('shared.challenges', ['shareToken' => $shareToken]);
        Log::debug('Generated URL', ['url' => $url]);
        
        return $url;
    }

    public function toggleFavorite()
    {
        $this->favorite = !$this->favorite;
        $this->challenge->update(['favorite' => $this->favorite]);
    }

    public function revokeAccess()
    {
        $this->challenge->sharing_token = null;
        $this->challenge->save();
    }

    public function confirmDeleteJournal($journalId)
    {
        $this->journalToDelete = JournalEntry::find($journalId);
        if ($this->journalToDelete && $this->journalToDelete->challenge->user_id === Auth::id()) {
            $this->showDeleteJournalModal = true;
        }
    }

    public function cancelDeleteJournal()
    {
        $this->showDeleteJournalModal = false;
        $this->journalToDelete = null;
    }

    public function deleteJournalEntry()
    {
        if ($this->journalToDelete && $this->journalToDelete->challenge->user_id === Auth::id()) {
            $this->journalToDelete->delete();
            $this->showDeleteJournalModal = false;
            $this->journalToDelete = null;
            $this->dispatch('notify', ['message' => 'Journal entry deleted successfully']);
        }
    }

    public function editJournalEntry($journalId)
    {
        $this->editingJournalId = $journalId;
        $this->dispatch('editJournalEntry', ['journalId' => $journalId]);
    }

    public function updatedSearch()
    {
        $this->resetPage();
    }

    public function updatedPrivacyFilter()
    {
        $this->resetPage();
    }

    public function render()
    {

        if (!$this->challenge) {
            return redirect()->route('dashboard');
        }

        $journalEntries = JournalEntry::where('challenge_id', $this->challenge->id)
            ->when($this->search, function ($query) {
                $query->where(function ($subQuery) {
                    $subQuery->where('journal_entries.title', 'like', '%' . $this->search . '%')
                        ->orWhere('journal_entries.content', 'like', '%' . $this->search . '%')
                        ->orWhereHas('tags', function ($q) {
                            $q->where('name', 'like', '%' . $this->search . '%');
                        });
                });
            })
            ->when($this->privacyFilter !== 'all', function ($query) {
                $query->where('is_private', $this->privacyFilter === 'private');
            })
            ->latest()
            ->paginate(5);

        return view('livewire.challenge-detail', compact('journalEntries'))
        ->layout('layouts.app');
    }
}