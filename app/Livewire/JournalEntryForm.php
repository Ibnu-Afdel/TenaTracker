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
    // public $content;
    public $date;
    public $images = [];
    public $code_snippets = [];
    public $tags = [];
    public $is_public = false;
    public $links = [];
    public $newUrl = '';
    public $newCaption = '';
    public $newCodeSnippet = '';
    public $newCodeLanguage = 'php';
    protected $rules = [
        'content' => 'required|string|min:10',
        'date' => 'required|date',
        'images.*' => 'nullable|image|max:2048',
        'code_snippets' => 'nullable|array',
        'code_snippets.*.code' => 'required|string',
        'code_snippets.*.language' => 'required|string',
        'tags' => 'nullable|array',
        'tags.*' => 'string|max:50',
        'links.*.url' => 'required|url',
        'links.*.caption' => 'nullable|string|max:255',
    ];

    protected $messages = [
        'content.required' => 'Journal content is required.',
        'content.min' => 'Content must be at least 10 characters.',
        'date.required' => 'Entry date is required.',
        'date.date' => 'Please enter a valid date.',
        'images.*.image' => 'Uploaded files must be images.',
        'images.*.max' => 'Image size must be under 2MB.',
        'code_snippets.*.code.required' => 'Code snippet content is required.',
        'code_snippets.*.language.required' => 'Programming language is required.',
        'tags.*.max' => 'Each tag cannot exceed 50 characters.',
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
        $this->reset(['content', 'date', 'images', 'code_snippets', 'tags', 'is_public', 'links', 'newUrl', 'newCaption', 'newCodeSnippet', 'newCodeLanguage']);
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
            $this->code_snippets = json_decode($entry->code_snippets ?? '[]', true) ?: [];
            $this->tags = json_decode($entry->tags ?? '[]', true) ?: [];
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

    public function addCodeSnippet()
    {
        if (!empty($this->newCodeSnippet)) {
            $this->code_snippets[] = [
                'code' => $this->newCodeSnippet,
                'language' => $this->newCodeLanguage
            ];
            $this->newCodeSnippet = '';
            $this->newCodeLanguage = 'php';
        }
    }

    public function removeCodeSnippet($index)
    {
        unset($this->code_snippets[$index]);
        $this->code_snippets = array_values($this->code_snippets);
    }

    public function handleTags($tagString)
    {
        $tags = array_map('trim', explode(',', $tagString));
        $this->tags = array_values(array_unique(array_filter($tags)));
    }

    public function removeTag($index)
    {
        unset($this->tags[$index]);
        $this->tags = array_values($this->tags);
    }

    public function removeImage($index)
    {
        unset($this->images[$index]);
        $this->images = array_values($this->images);
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
                'code_snippets' => json_encode($this->code_snippets),
                'tags' => json_encode($this->tags),
            ]
        );

        if (!empty($this->images)) {
            $imagePaths = [];
            foreach ($this->images as $image) {
                $imagePaths[] = $image->store('journal-images', 'public');
            }
            $entry->update(['images' => json_encode($imagePaths)]);
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
