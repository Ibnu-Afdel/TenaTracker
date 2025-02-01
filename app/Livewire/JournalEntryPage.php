<?php

namespace App\Livewire;

use App\Models\JournalEntry;
use App\Models\Challenge;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\Attributes\Rule;
use Livewire\WithFileUploads;
use Livewire\Features\SupportFileUploads\TemporaryUploadedFile;
use Illuminate\Support\Facades\Storage;

class JournalEntryPage extends Component
{
    use WithFileUploads;

    // Block type constants
    const TYPE_TEXT = 'text';
    const TYPE_CODE = 'code';
    const TYPE_IMAGE = 'image';

    public ?int $challengeId = null;

    public string $title = '';
    public string $date;
    public array $tags = [];
    public string $tagInput = '';
    public array $blocks = [];
    public array $shared_links = [];
    public array $newLink = [
        'url' => '',
        'caption' => ''
    ];

    public $is_private = false;

    public function getShareUrlProperty()
    {
        if (!$this->is_private && $this->challengeId) {
            $entry = JournalEntry::where('challenge_id', $this->challengeId)
                            ->where('user_id', Auth::id())
                            ->first();
            if ($entry && $entry->shared_link) {
                return route('journal.shared', ['token' => $entry->shared_link]);
            }
        }
        return null;
    }

    public function rules(): array
    {
        return [
            'title' => ['required', 'min:3', 'max:255'],
            'date' => ['required', 'date'],
            'tags' => ['array'],
            'shared_links' => ['array'],
            'shared_links.*.url' => ['required', 'url'],
            'shared_links.*.caption' => ['required', 'string', 'max:255'],
            'newLink.url' => ['nullable', 'url'],
            'newLink.caption' => ['nullable', 'string', 'max:255'],
            'blocks' => ['required', 'array', 'min:1'],
            'blocks.*.type' => ['required', 'string', 'in:text,code,image'],
            'blocks.*.content' => [
                'required_if:blocks.*.type,text,code',
                function ($attribute, $value, $fail) {
                    $index = explode('.', $attribute)[1];
                    $blockType = $this->blocks[$index]['type'] ?? null;
                    
                    if ($blockType === 'text' && empty(trim($value))) {
                        $fail('Text content cannot be empty.');
                    }
                    if ($blockType === 'code' && empty(trim($value))) {
                        $fail('Code content cannot be empty.');
                    }
                }
            ],
            'blocks.*.upload' => [
                'nullable',
                'required_if:blocks.*.type,image',
                'image',
                'max:10240'
            ],
            'blocks.*.metadata' => ['array'],
            'blocks.*.metadata.language' => [
                'required_if:blocks.*.type,code',
                'string'
            ],
            'blocks.*.metadata.alt' => [
                'nullable',
                'string',
                'max:255'
            ]
        ];
    }

    public function messages(): array
    {
        return [
            'blocks.required' => 'At least one content block is required.',
            'blocks.min' => 'Please add at least one content block to your journal entry.',
            'blocks.*.type.required' => 'Each block must have a type specified.',
            'blocks.*.type.in' => 'Block type must be text, code, or image.',
            'blocks.*.content.required_if' => 'Content is required for text and code blocks.',
            'blocks.*.upload.required_if' => 'Please select an image to upload.',
            'blocks.*.upload.image' => 'The file must be an image.',
            'blocks.*.upload.max' => 'Image size cannot exceed 10MB.',
            'blocks.*.metadata.language.required_if' => 'Programming language must be specified for code blocks.',
            'title.required' => 'Please provide a title for your journal entry.',
            'title.min' => 'Title must be at least 3 characters long.',
            'date.required' => 'Please specify a date for your journal entry.',
            'date.date' => 'Please provide a valid date.'
        ];
    }
    
    public function mount($challengeId = null)
    {
        $this->challengeId = $challengeId;
        $this->date = now()->format('Y-m-d');
        $this->blocks = []; // Initialize empty blocks array
        $this->tags = [];
        
        if ($challengeId) {
            $challenge = Challenge::findOrFail($challengeId);
            $this->title = 'Journal Entry for ' . $challenge->title;
        }
    }

    protected function processImageUpload($block, $index)
    {
        if (isset($block['upload']) && $block['upload'] instanceof TemporaryUploadedFile) {
            // Delete old image if it exists
            if (isset($block['content']) && 
                !($block['content'] instanceof TemporaryUploadedFile) && 
                Storage::disk('public')->exists($block['content'])) {
                Storage::disk('public')->delete($block['content']);
            }
            
            // Store new image
            $path = $block['upload']->store('journal-images', 'public');
            return $path;
        }
        return $block['content'] ?? null;
    }

    public function updatedBlocks($value, $key)
    {
        if (str_contains($key, 'upload')) {
            $index = explode('.', $key)[0];
            if (isset($this->blocks[$index]['upload']) && 
                $this->blocks[$index]['upload'] instanceof TemporaryUploadedFile) {
                // Store the temporary upload object directly
                $this->blocks[$index]['content'] = $this->blocks[$index]['upload'];
            }
        }
    }

    public function addBlock(string $type)
    {
        $block = [
            'type' => $type,
            'content' => '',
            'metadata' => []
        ];
        
        if ($type === self::TYPE_CODE) {
            $block['metadata']['language'] = 'php';
        } elseif ($type === self::TYPE_IMAGE) {
            $block['metadata']['alt'] = '';
        }
        
        $this->blocks[] = $block;
    }

    public function removeBlock(int $index)
    {
        if (isset($this->blocks[$index])) {
            // If it's an image block, clean up the stored file
            if ($this->blocks[$index]['type'] === self::TYPE_IMAGE &&
                isset($this->blocks[$index]['content']) &&
                !($this->blocks[$index]['content'] instanceof TemporaryUploadedFile) &&
                Storage::disk('public')->exists($this->blocks[$index]['content'])) {
                Storage::disk('public')->delete($this->blocks[$index]['content']);
            }
            unset($this->blocks[$index]);
            $this->blocks = array_values($this->blocks);
        }
    }

    public function moveBlockUp(int $index)
    {
        if ($index > 0 && isset($this->blocks[$index])) {
            $temp = $this->blocks[$index - 1];
            $this->blocks[$index - 1] = $this->blocks[$index];
            $this->blocks[$index] = $temp;
        }
    }

    public function moveBlockDown(int $index)
    {
        if ($index < count($this->blocks) - 1 && isset($this->blocks[$index])) {
            $temp = $this->blocks[$index + 1];
            $this->blocks[$index + 1] = $this->blocks[$index];
            $this->blocks[$index] = $temp;
        }
    }
    
    public function addCodeSnippet()
    {
        $this->code_snippets[] = [
            'code' => '',
            'language' => 'php'
        ];
    }

    public function removeCodeSnippet($index)
    {
        if (isset($this->code_snippets[$index])) {
            unset($this->code_snippets[$index]);
            $this->code_snippets = array_values($this->code_snippets);
        }
    }

    public function addTag($tag = null)
    {
        $tag = $tag ?? trim($this->tagInput);
        if ($tag && !in_array($tag, $this->tags)) {
            $this->tags[] = $tag;
            $this->tagInput = '';
        }
    }

    public function addTagFromInput($keyCode)
    {
        // Handle Enter (13) or Tab (9)
        if (in_array($keyCode, [9, 13])) {
            if (!empty($this->tagInput)) {
                $this->addTag();
            }
            // Prevent form submission and default behavior
            $this->dispatch('prevent-submit');
            return false;
        }
        return true;
    }

    public function removeTag($index)
    {
        if (isset($this->tags[$index])) {
            unset($this->tags[$index]);
            $this->tags = array_values($this->tags);
        }
    }

    public function removeImage($index)
    {
        if (isset($this->images[$index])) {
            unset($this->images[$index]);
            $this->images = array_values($this->images);
        }
    }

    public function addLink()
    {
        $this->validateOnly('newLink.url', [
            'newLink.url' => ['required', 'url'],
            'newLink.caption' => ['required', 'string', 'max:255'],
        ]);

        $this->shared_links[] = [
            'url' => $this->newLink['url'],
            'caption' => $this->newLink['caption']
        ];

        // Reset the form
        $this->newLink = [
            'url' => '',
            'caption' => ''
        ];
    }

    public function removeLink($index)
    {
        if (isset($this->shared_links[$index])) {
            unset($this->shared_links[$index]);
            $this->shared_links = array_values($this->shared_links);
        }
    }

    public function save()
    {
        $this->validate();

        // Process all blocks before saving
        foreach ($this->blocks as $index => $block) {
            if ($block['type'] === self::TYPE_IMAGE) {
                $this->blocks[$index]['content'] = $this->processImageUpload($block, $index);
            }
        }

        // Create the journal entry
        $journalEntry = JournalEntry::create([
            'user_id' => Auth::id(),
            'challenge_id' => $this->challengeId,
            'title' => $this->title,
            'content' => null,
            'date' => $this->date,
            'blocks' => $this->blocks,
            'tags' => $this->tags,
            'is_private' => $this->is_private,
        ]);

        // Create the links
        foreach ($this->shared_links as $link) {
            $journalEntry->links()->create([
                'url' => $link['url'],
                'caption' => $link['caption']
            ]);
        }

        session()->flash('message', 'Journal entry saved successfully!');
        return $this->redirectRoute('challenges.detail', ['challengeId' => $this->challengeId]);
    }
    
    public function render()
    {
        return view('livewire.journal-entry-page')
            ->layout('layouts.app');
    }
}

