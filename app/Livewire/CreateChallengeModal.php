<?php

namespace App\Livewire;

use App\Models\Tag;
use App\Models\Challenge;
use Livewire\Component;
use Illuminate\Support\Facades\Auth;

class CreateChallengeModal extends Component
{
    public $showForm = false;
    public $isEditing = false;
    public $editingChallengeId = null;
    public $name = '';
    public $description = '';
    public $start_date = '';
    public $end_date = '';
    public $is_favorite = false;
    public $tags = [];
    public $tagInput = '';

    protected $rules = [
        'name' => 'required|min:3',
        'description' => 'required',
        'start_date' => 'required|date',
        'end_date' => 'required|date|after_or_equal:start_date',
    ];

    protected $listeners = [
        'openCreateChallengeModal' => 'showModal',
        'editChallenge'
    ];

    

    public function editChallenge($challengeId)
    {
        $challenge = Challenge::with('tags')->findOrFail($challengeId);
        
        $this->editingChallengeId = $challengeId;
        $this->name = $challenge->name;
        $this->description = $challenge->description;
        $this->start_date = $challenge->start_date;
        $this->end_date = $challenge->end_date;
        $this->is_favorite = $challenge->is_favorite;
        $this->tags = $challenge->tags->pluck('name')->toArray();
        
        $this->isEditing = true;
        $this->showForm = true;
    }

    public function showModal()
    {
        $this->reset(['isEditing', 'editingChallengeId', 'name', 'description', 'start_date', 'end_date', 'is_favorite', 'tags', 'tagInput']);
        $this->showForm = true;
    }

    public function closeModal()
    {
        $this->showForm = false;
        $this->isEditing = false;
        $this->reset(['editingChallengeId', 'name', 'description', 'start_date', 'end_date', 'is_favorite', 'tags', 'tagInput']);
    }

    public function save()
    {
        $this->validate();

        if ($this->isEditing) {
            $challenge = Challenge::findOrFail($this->editingChallengeId);
            $challenge->update([
                'name' => $this->name,
                'description' => $this->description,
                'start_date' => $this->start_date,
                'end_date' => $this->end_date,
                'is_favorite' => $this->is_favorite,
            ]);
        } else {
            $challenge = Challenge::create([
                'name' => $this->name,
                'description' => $this->description,
                'start_date' => $this->start_date,
                'end_date' => $this->end_date,
                'user_id' => Auth::id(),
                'is_favorite' => $this->is_favorite,
            ]);
        }

        if (!empty($this->tags)) {
            $tagIds = [];
            foreach ($this->tags as $tagName) {
                $tag = Tag::firstOrCreate(
                    ['name' => $tagName, 'user_id' => Auth::id()],
                    ['name' => $tagName, 'user_id' => Auth::id()]
                );
                $tagIds[] = $tag->id;
            }
            $challenge->tags()->sync($tagIds);
        } else {
            $challenge->tags()->detach();
        }

        $this->closeModal();
        $this->dispatch('challengeCreated');
    }


    public function addTagFromInput()
{
    if (!empty($this->tagInput)) {
        $this->addTag($this->tagInput);
        $this->tagInput = '';
    }
}

public function addTag($tag)
{
    $tag = trim($tag);
    if (!empty($tag) && !in_array($tag, $this->tags)) {
        $this->tags[] = $tag;
    }
}

public function removeTag($index)
{
    unset($this->tags[$index]);
    $this->tags = array_values($this->tags);
}

    public function render()
    {
        return view('livewire.create-challenge-modal');
    }
}