<?php

namespace App\Livewire;

use App\Models\Challenge;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\WithPagination;

class ChallengesDashboard extends Component
{

    use WithPagination;

    public $search = '';
    public $statusFilter = 'all';
    public $favoritesOnly = false;

    protected $updatesQueryString = ['search', 'statusFilter', 'favoritesOnly'];

    public function updateSearch() {
        $this->resetPage();
    }

    public function toggleFavorite($challengeID){
        $challenge = Challenge::findOrFail($challengeID);
        if ($challenge->user_id === Auth::id()){
            $challenge->is_favorite != $challenge->is_favorite;
            $challenge->save();
        }
    }

    public function render()
    {

        $challenges = Challenge::where('user_id', Auth::id())
        ->when($this->search, function($query){
            $query->where('name', 'like', "%{$this->search}%");
        })
        ->when($this->statusFilter !== 'all', function($query){
            $query->where('status', $this->statusFilter);
        })
        ->when($this->favoritesOnly, function($query){
            $query->where('is_favorite', true);
        })
        ->latest()->paginate(5);
        return view('livewire.challenges-dashboard', compact('challenges'));
    }
}
