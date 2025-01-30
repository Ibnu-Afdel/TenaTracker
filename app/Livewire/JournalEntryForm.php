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

    public $showJournalModal = false;
    public $challengeId = null;
    public $entryId = null;
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
        'links.*.url' => 'required|url',
        'links.*.caption' => 'nullable|string|max:255',
    ];

    protected $messages = [
        'content.required' => 'Journal content is required.',
        'content.min' => 'Content must be at least 10 characters.',
        'date.required' => 'Entry date is required.',
        'date.date' => 'Please enter a valid date.',
        'image.image' => 'Uploaded file must be an image.',
        'image.max' => 'Image size must be under 2MB.',
        'code_snippet.string' => 'Code snippet must be text.',
        'links.*.url.required' => 'URL is required for each link.',
        'links.*.url.url' => 'Please enter a valid URL.',
        'links.*.caption.max' => 'Link caption cannot exceed 255 characters.',
    ];

    protected $listeners = [
        'openJournalModal' => 'openModal'
    ];

    public function openModal($challengeId = null)
    {
        logger()->debug('Opening modal with challenge ID: ' . $challengeId);
        $this->challengeId = $challengeId;
        $this->resetForm();
        $this->showJournalModal = true;
    }

    public function closeJournalEntryForm()
    {
        logger()->debug('Hiding modal for challenge ID: ' . $this->challengeId);
        $this->showJournalModal = false;
        $this->resetForm();
        $this->dispatch('journalEntryFormClosed');
    }

    protected function resetForm()
    {
        $this->reset(['content', 'date', 'image', 'code_snippet', 'is_public', 'links', 'newUrl', 'newCaption']);
    }

    public function mount($challengeId = null, $entryId = null)
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
            // $this->links = $entry->links->map(function ($link) {
            //     return ['url' => $link->url, 'caption' => $link->caption];
            // })->toArray();
            $this->links = $entry->links ? $entry->links->map(fn ($link) => ['url' => $link->url, 'caption' => $link->caption])->toArray() : [];

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
        if (!$this->challengeId) {
            $this->addError('general', 'Challenge ID is required');
            return;
        }
        
        $this->validate();
        
        try {

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

        $this->hideModal();
        $this->dispatch('journalEntryCreated', [
            'entryId' => $entry->id,
            'challengeId' => $this->challengeId
        ]);
    } catch (\Exception $e) {
        $this->addError('general', 'Failed to save journal entry: ' . $e->getMessage());
    }
}


    

    public function render()
    {
        return view('livewire.journal-entry-form');
    }
}
