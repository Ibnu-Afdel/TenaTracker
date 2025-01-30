<?php

namespace App\Livewire;

use App\Models\JournalEntry;
use App\Models\Challenge;
use Livewire\Component;
use Livewire\Attributes\Rule;

class JournalEntryPage extends Component
{
    public ?int $challengeId = null;

    #[Rule('required|min:3|max:255')]
    public string $title = '';

    #[Rule('required|min:10')]
    public string $content = '';

    #[Rule('required|date')]
    public string $date;

    #[Rule('nullable')]
    public ?string $code_snippet = null;

    #[Rule('nullable|url')]
    public ?string $shared_link = null;
    
    public function mount($challengeId = null)
    {
        $this->challengeId = $challengeId;
        $this->date = now()->format('Y-m-d');
        
        if ($challengeId) {
            $challenge = Challenge::findOrFail($challengeId);
            $this->title = 'Journal Entry for ' . $challenge->title;
        }
    }
    
    public function save()
    {
        $this->validate();
        
        JournalEntry::create([
            'user_id' => auth()->id(),
            'challenge_id' => $this->challengeId,
            'title' => $this->title,
            'content' => $this->content,
            'date' => $this->date,
            'code_snippet' => $this->code_snippet,
            'shared_link' => $this->shared_link,
        ]);
        
        session()->flash('message', 'Journal entry saved successfully!');
        return $this->redirectRoute('challenges.detail', ['challengeId' => $this->challengeId]);
    }
    
    public function render()
    {
        return view('livewire.journal-entry-page')
            ->layout('layouts.app');
    }
}

