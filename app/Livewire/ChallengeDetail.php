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
    
    public $statusOptions = ['Ongoing', 'Completed', 'Paused'];

    protected $listeners = [
        'journalEntryCreated' => '$refresh'
    ];

    public function mount($challengeId = null, $shareToken = null)
    {
        if ($shareToken) {
            $this->shareToken = $shareToken;
            $this->challenge = Challenge::where('sharing_token', $shareToken)
                ->with('tags', 'journalEntries')
                ->firstOrFail();
        } else {
            $this->challenge = Challenge::where('user_id', Auth::id())
                ->with('tags', 'journalEntries')
                ->findOrFail($challengeId);
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

    public function render()
    {

        $journalEntries = JournalEntry::where('challenge_id', $this->challenge->id)
        ->latest()
        ->paginate(5);

        return view('livewire.challenge-detail', compact('journalEntries'))->with('layout', 'layouts.app'); 
        // ->layout('layouts.app');
    }
}
