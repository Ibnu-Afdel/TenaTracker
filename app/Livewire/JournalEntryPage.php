<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\JournalEntry;
use App\Models\Challenge;
use Illuminate\Support\Facades\Auth;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\Storage;
use Livewire\Features\SupportFileUploads\TemporaryUploadedFile;
use Livewire\Attributes\Rule;

class JournalEntryPage extends Component
{
    use WithFileUploads;

    // Block type constants
    const TYPE_TEXT = 'text';
    const TYPE_CODE = 'code';
    const TYPE_IMAGE = 'image';

    #[Rule(['nullable', 'exists:challenges,id'])]
    public ?int $challengeId = null;

    public string $title = '';
    public string $date;
    private int $dayNumber = 1;
    public bool $is_private = false; 

    public array $tags = [];
    public string $tagInput = '';
    public array $blocks = [];
    public array $shared_links = [];
    public array $newLink = [
        'url' => '',
        'caption' => ''
    ];
    public function rules(): array
    {
        return [
            'title' => ['required', 'min:3', 'max:255'],
            'date' => ['required', 'date'],
            'tags' => ['array'],
            'shared_links' => ['nullable', 'array'],
            'shared_links.*.url' => ['nullable', 'url'],
            'shared_links.*.caption' => ['nullable', 'string', 'max:255'],
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
    
    public function mount($challengeId = null, $entry = null)
    {
        $this->date = now()->format('Y-m-d');
        $this->blocks = []; // Initialize empty blocks array
        $this->tags = [];

        if ($entry) {
            $journalEntry = JournalEntry::findOrFail($entry);
            $this->challengeId = $journalEntry->challenge_id;
            $this->title = $journalEntry->title;
            $this->date = $journalEntry->date;
            $this->blocks = $journalEntry->blocks;
            $this->tags = $journalEntry->tags;
            $this->is_private = $journalEntry->is_private;
            
            foreach ($journalEntry->links as $link) {
                $this->shared_links[] = [
                    'url' => $link->url,
                    'caption' => $link->caption
                ];
            }
        } else {
            $this->challengeId = $challengeId;
            if ($challengeId) {
                $challenge = Challenge::findOrFail($challengeId);
                $this->dayNumber = $this->calculateDayNumber();
                $this->title = 'Day ' . $this->dayNumber . ' - Journal Entry for ' . $challenge->title;
            }
        }
    }

    protected function processImageUpload($block, $index)
    {
        if (isset($block['upload']) && $block['upload'] instanceof TemporaryUploadedFile) {
            // Delete old image if it exists
            if (isset($block['content']) && 
                !($block['content'] instanceof TemporaryUploadedFile)) {
                // Clean up old path and delete if exists
                $oldPath = str_replace('public/', '', $block['content']);
                if (Storage::disk('public')->exists($oldPath)) {
                    Storage::disk('public')->delete($oldPath);
                }
            }
            
            // Store new image and ensure proper path
            $path = $block['upload']->store('journal-images', 'public');
            // Clean up the path to ensure consistency
            return str_replace('public/', '', $path);
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
        if (in_array($keyCode, [9, 13])) {
            if (!empty($this->tagInput)) {
                $this->addTag();
            }
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

        $data = [
            'user_id' => Auth::id(),
            'challenge_id' => $this->challengeId,
            'title' => $this->title,
            'content' => null,
            'date' => $this->date,
            'blocks' => $this->blocks,
            'tags' => $this->tags,
            'is_private' => $this->is_private,
        ];

        $journalEntry = JournalEntry::updateOrCreate(
            ['id' => request()->route('entry')],
            $data
        );

        // Process shared links if they exist
        if (!empty($this->shared_links)) {
            foreach ($this->shared_links as $link) {
                if (!empty($link['url'])) {
                    $journalEntry->links()->create([
                        'url' => $link['url'],
                        'caption' => $link['caption'] ?? ''
                    ]);
                }
            }
        }

        session()->flash('message', 'Journal entry saved successfully!');
        return $this->redirectRoute('challenges.detail', ['challengeId' => $this->challengeId]);
    }
    
    public function render()
    {
        return view('livewire.journal-entry-page')
            ->layout('layouts.app');
    }

    protected function calculateDayNumber()
    {
        if (!$this->challengeId) {
            return 1;
        }
        
        return JournalEntry::where('challenge_id', $this->challengeId)
                    ->orderBy('created_at')
                    ->count() + 1;
    }
}
