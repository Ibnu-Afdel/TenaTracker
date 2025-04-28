<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\JournalEntry;
use Illuminate\Support\Facades\Auth;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\Storage;
use Livewire\Features\SupportFileUploads\TemporaryUploadedFile;

class JournalRichTextPage extends JournalEntryPage
{
    public string $editorContent = '';
    
    public function mount($challengeId = null, $entry = null)
    {
        parent::mount($challengeId, $entry);
        
        // If we're editing an existing entry, convert the blocks to rich text format
        if ($this->entryId) {
            $this->convertBlocksToRichText();
        }
    }
    
    /**
     * Convert existing blocks to rich text content
     */
    protected function convertBlocksToRichText()
    {
        $content = '';
        
        foreach ($this->blocks as $block) {
            if ($block['type'] === self::TYPE_TEXT) {
                $content .= '<p>' . nl2br(e($block['content'])) . '</p>';
            } elseif ($block['type'] === self::TYPE_CODE) {
                $language = $block['metadata']['language'] ?? 'php';
                $content .= '<pre class="ql-syntax" data-language="' . $language . '">'
                    . e($block['content'])
                    . '</pre>';
            } elseif ($block['type'] === self::TYPE_IMAGE) {
                if (isset($block['content']) && !empty($block['content'])) {
                    // Fix: Using asset() to properly generate URL to public storage
                    $imageUrl = asset('storage/' . $block['content']);
                    $alt = $block['metadata']['alt'] ?? '';
                    $content .= '<p><img src="' . $imageUrl . '" alt="' . e($alt) . '"></p>';
                }
            }
        }
        
        $this->editorContent = $content;
    }
    
    /**
     * Convert rich text content back to blocks before saving
     */
    protected function convertRichTextToBlocks()
    {
        $dom = new \DOMDocument();
        libxml_use_internal_errors(true);
        $dom->loadHTML(mb_convert_encoding($this->editorContent, 'HTML-ENTITIES', 'UTF-8'));
        libxml_clear_errors();
        
        $this->blocks = [];
        
        $elements = $dom->getElementsByTagName('body')->item(0)->childNodes;
        
        foreach ($elements as $element) {
            if ($element->nodeType !== XML_ELEMENT_NODE) {
                continue;
            }
            
            $tagName = $element->tagName;
            
            if (in_array($tagName, ['p', 'h1', 'h2', 'h3', 'h4', 'h5', 'h6', 'div', 'blockquote'])) {
                // Check if it's an image
                $images = $element->getElementsByTagName('img');
                if ($images->length > 0) {
                    $img = $images->item(0);
                    $src = $img->getAttribute('src');
                    
                    // Handle image URLs
                    if (strpos($src, 'data:image') === 0) {
                        // It's a new base64 image
                        $imageData = file_get_contents($src);
                        $extension = explode('/', mime_content_type($src))[1];
                        $filename = 'journal-images/' . uniqid() . '.' . $extension;
                        Storage::disk('public')->put($filename, $imageData);
                        
                        $this->blocks[] = [
                            'type' => self::TYPE_IMAGE,
                            'content' => $filename,
                            'metadata' => [
                                'alt' => $img->getAttribute('alt') ?? ''
                            ]
                        ];
                    } else if (strpos($src, '/storage/') !== false) {
                        // It's an existing image
                        // Extract the path after /storage/ regardless of domain
                        $pattern = '/\/storage\/(.*?)$/';
                        if (preg_match($pattern, $src, $matches)) {
                            $path = $matches[1];
                            $this->blocks[] = [
                                'type' => self::TYPE_IMAGE,
                                'content' => $path,
                                'metadata' => [
                                    'alt' => $img->getAttribute('alt') ?? ''
                                ]
                            ];
                        }
                    }
                } else {
                    // Regular text content
                    $this->blocks[] = [
                        'type' => self::TYPE_TEXT,
                        'content' => $element->textContent,
                        'metadata' => []
                    ];
                }
            } else if ($tagName === 'pre') {
                // Code block
                $language = $element->getAttribute('data-language') ?? 'php';
                $this->blocks[] = [
                    'type' => self::TYPE_CODE,
                    'content' => $element->textContent,
                    'metadata' => [
                        'language' => $language
                    ]
                ];
            }
        }
    }
    
    /**
     * Handle file uploads from the rich text editor
     */
    public function uploadEditorImage($imageData)
    {
        if (empty($imageData)) {
            return null;
        }
        
        try {
            $imageData = explode(',', $imageData)[1] ?? '';
            $imageData = base64_decode($imageData);
            
            if (!$imageData) {
                return null;
            }
            
            $filename = 'journal-images/' . uniqid() . '.png';
            Storage::disk('public')->put($filename, $imageData);
            
            // Fix: Using asset() to properly generate URL to public storage
            return [
                'url' => asset('storage/' . $filename)
            ];
        } catch (\Exception $e) {
            return [
                'error' => $e->getMessage()
            ];
        }
    }
    
    /**
     * Override save method to convert rich text to blocks first
     */
    public function save()
    {
        try {
            // Log the start of save process for debugging
            \Log::info('Starting journal save process', [
                'entryId' => $this->entryId,
                'editorContentLength' => strlen($this->editorContent),
                'user' => Auth::id()
            ]);
            
            // First convert the rich text content back to blocks
            try {
                $this->convertRichTextToBlocks();
                \Log::info('Successfully converted rich text to blocks', [
                    'blockCount' => count($this->blocks)
                ]);
            } catch (\Exception $e) {
                \Log::error('Error converting rich text to blocks', [
                    'error' => $e->getMessage(),
                    'trace' => $e->getTraceAsString(),
                    'editorContent' => substr($this->editorContent, 0, 200) . '...' // Log partial content for debugging
                ]);
                session()->flash('error', 'Error processing rich text content: ' . $e->getMessage());
                return false;
            }
            
            // Then call the parent save method
            try {
                $result = parent::save();
                \Log::info('Journal save completed successfully', [
                    'entryId' => $this->entryId,
                    'result' => $result
                ]);
                return $result;
            } catch (\Exception $e) {
                \Log::error('Error in parent save method', [
                    'error' => $e->getMessage(),
                    'trace' => $e->getTraceAsString(),
                    'blocks' => json_encode(array_slice($this->blocks, 0, 3)) // Log first few blocks for debugging
                ]);
                session()->flash('error', 'Error saving journal entry: ' . $e->getMessage());
                return false;
            }
        } catch (\Exception $e) {
            // Catch any other unexpected errors
            \Log::error('Unexpected error in journal save process', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            session()->flash('error', 'An unexpected error occurred: ' . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Render the component with a different view
     */
    public function render()
    {
        return view('livewire.journal-rich-text-page')
            ->layout('layouts.app');
    }
}

