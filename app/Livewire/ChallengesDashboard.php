<?php

namespace App\Livewire;

use App\Models\Challenge;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\Attributes\Computed;
use Livewire\Attributes\On;

class ChallengesDashboard extends Component
{
    public $search = '';
    public $statusFilter = 'all';
    public $favoritesOnly = false;

    #[On('challengeCreated')] 
    public function refreshChallenges()
    {
        // This method will be called when 'challengeCreated' event is emitted
        // The #[Computed] property will automatically refresh
    }

    public function toggleFavorite($challengeId)
    {
        $challenge = Challenge::where('user_id', Auth::id())
            ->findOrFail($challengeId);
        
        $challenge->is_favorite = !$challenge->is_favorite;
        $challenge->save();
    }

    public function toggleFavorites()
    {
        $this->favoritesOnly = !$this->favoritesOnly;
    }

    #[Computed]
    public function challenges()
    {
        return Challenge::query()
            ->where('user_id', Auth::id())
            ->when($this->search, function ($query) {
                $query->where(function($q) {
                    $q->where('name', 'like', '%' . $this->search . '%')
                    ->orWhere('description', 'like', '%' . $this->search . '%');
                });
            })
            ->when($this->statusFilter !== 'all', function ($query) {
                $query->where('status', $this->statusFilter);
            })
            ->when($this->favoritesOnly, function ($query) {
                $query->where('is_favorite', true);
            })
            ->latest()
            ->get();
    }

    public function render()
    {
        return view('livewire.challenges-dashboard')
            ->layout('layouts.app', [
                'header' => 'Challenges Dashboard'
            ]);
    }
}
