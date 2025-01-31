<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Challenge;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class SharedChallenge extends Component
{
    public $challenge;
    public $shareToken;
    public $notFound = false;

    public function mount($shareToken)
    {
        $this->shareToken = $shareToken;
        
        try {
            $this->challenge = Challenge::where('sharing_token', $shareToken)
                ->with(['journalEntries' => function ($query) {
                    $query->orderBy('created_at', 'desc');
                }, 'tags'])
                ->firstOrFail();
        } catch (ModelNotFoundException $e) {
            $this->notFound = true;
        }
    }

    public function render()
    {
        return view('livewire.shared-challenge')
            ->layout('layouts.app');
    }
}

