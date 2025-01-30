<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithFileUploads;
use App\Models\JournalEntry;
use App\Models\JournalLink;
use Illuminate\Support\Facades\Auth;

class JournalEntryForm extends Component
{
    use WithFileUploads;

    public $challengeId;
    public $entryId; 
    public $content;
    public $date;
    public $image;
    public $code_snippet;
    public $is_public = false;
    public $links = [];
    public $newUrl = '';
    public $newCaption = '';

    protected $rules = [
        'content' => 'required|string|min:10',
        'date' => 'required|date',
        'image' => 'nullable|image|max:2048',
        'code_snippet' => 'nullable|string',
        'links.*.url' => 'url',
    ];

    protected $messages = [
        'content.required' => 'Journal content is required.',
        'content.min' => 'Content must be at least 10 characters.',
        'date.required' => 'Entry date is required.',
        'image.image' => 'Uploaded file must be an image.',
        'image.max' => 'Image size must be under 2MB.',
        'links.*.url' => 'Each link must be a valid URL.',
    ];

    protected $listeners = ['openJournalEntryForm' => 'showModal'];

    public function showModal()
    {
        $this->dispatch('openJournalModal');
    }

    public function mount($challengeId, $entryId = null)
    {
        $this->challengeId = $challengeId;

        if ($entryId) {
            $entry = JournalEntry::where('id', $entryId)
                ->where('challenge_id', $challengeId)
                ->firstOrFail();

            $this->entryId = $entry->id;
            $this->content = $entry->content;
            $this->date = $entry->date;
            $this->is_public = $entry->is_public;
            $this->code_snippet = $entry->code_snippet;
            $this->links = $entry->links->map(function ($link) {
                return ['url' => $link->url, 'caption' => $link->caption];
            })->toArray();
        }
    }

    public function addLink()
    {
        if (filter_var($this->newUrl, FILTER_VALIDATE_URL)) {
            $this->links[] = ['url' => $this->newUrl, 'caption' => $this->newCaption];
            $this->newUrl = '';
            $this->newCaption = '';
        }
    }

    public function removeLink($index)
    {
        unset($this->links[$index]);
        $this->links = array_values($this->links);
    }

    public function saveJournalEntry()
    {
        $this->validate();

        $entry = JournalEntry::updateOrCreate(
            ['id' => $this->entryId],
            [
                'challenge_id' => $this->challengeId,
                'user_id' => Auth::id(),
                'content' => $this->content,
                'date' => $this->date,
                'is_public' => $this->is_public,
                'code_snippet' => $this->code_snippet,
            ]
        );

        if ($this->image) {
            $imagePath = $this->image->store('journal-images', 'public');
            $entry->update(['image' => $imagePath]);
        }

        JournalLink::where('journal_entry_id', $entry->id)->delete();
        foreach ($this->links as $link) {
            JournalLink::create([
                'journal_entry_id' => $entry->id,
                'url' => $link['url'],
                'caption' => $link['caption'] ?? '',
            ]);
        }

        $this->reset(['content', 'date', 'image', 'code_snippet', 'is_public', 'links']);
        $this->dispatch('journalEntryCreated');
        $this->dispatch('closeJournalModal');
    }

    public function render()
    {
        return view('livewire.journal-entry-form')->layout('layouts.app');
    }
}
