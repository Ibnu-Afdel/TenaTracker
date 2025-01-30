<?php

namespace App\Livewire;

use App\Models\Challenge;
use App\Models\Tag;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\WithPagination;

class CreateChallengeModal extends Component
{
    use WithPagination;

    public $name;
    public $description;
    public $start_date;
    public $end_date;
    public $is_favorite = false;
    public $selectedTags = [];
    public $allTags = [];

    protected $rules = [
        'name' => 'required|string|min:3|max:255',
        'description' => 'nullable|string|max:1000',
        'start_date' => 'required|date',
        'end_date' => 'nullable|date|after_or_equal:start_date',
        'selectedTags' => 'array',
    ];

    protected $messages = [
        'name.required' => 'Challenge name is required.',
        'name.min' => 'Challenge name must be at least 3 characters.',
        'start_date.required' => 'Start date is required.',
        'end_date.after_or_equal' => 'End date must be after or equal to the start date.',
    ];

    public function mount()
    {
        $this->allTags = Tag::where('user_id', Auth::id())->pluck('name', 'id');
    }

    public function createChallenge()
    {
        $this->validate();

        $challenge = Challenge::create([
            'name' => $this->name,
            'description' => $this->description,
            'start_date' => $this->start_date,
            'end_date' => $this->end_date,
            'user_id' => Auth::id(),
            'is_favorite' => $this->is_favorite,
        ]);
        if (!empty($this->selectedTags)) {
            $challenge->tags()->attach($this->selectedTags);
        }

        
        $this->reset(['name', 'description', 'start_date', 'end_date', 'is_favorite', 'selectedTags']);

        // Emit event to refresh ChallengesDashboard
        $this->dispatch('challengeCreated');

        // Close modal
        $this->dispatch('closeModal');

    }


    public function render()
    {
        return view('livewire.create-challenge-modal');
    }
}
