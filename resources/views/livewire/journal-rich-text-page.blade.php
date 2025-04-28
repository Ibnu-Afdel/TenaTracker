@php
    use Illuminate\Support\Facades\Storage;
@endphp
<div class="py-12">
    <div>
        <div class="overflow-hidden bg-white shadow-xl sm:rounded-lg">
            <div class="p-6">
                <h2 class="mb-4 text-2xl font-bold">Create Journal Entry</h2>
                
                @if (session()->has('message'))
                    <div class="relative px-4 py-3 mb-4 text-green-700 bg-green-100 border border-green-400 rounded" role="alert">
                        <span class="block sm:inline">{{ session('message') }}</span>
                    </div>
                @endif
                
                <form wire:submit="save" class="space-y-6">
                    <div>
                        <label for="title" class="block text-sm font-medium text-gray-700">Title</label>
                        <input type="text" wire:model="title" id="title" 
                            @class([
                                'block w-full mt-1 rounded-md shadow-sm sm:text-sm',
                                'border-red-300 focus:border-red-500 focus:ring-red-500' => $errors->has('title'),
                                'border-gray-300 focus:border-indigo-500 focus:ring-indigo-500' => !$errors->has('title'),
                            ])>
                        @error('title') 
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div x-data="{ 
                        editorInstance: null,
                        editorInitialized: false,
                        initEditor() {
                            // Prevent reinitialization if already initialized
                            if (this.editorInitialized) return;
                            
                            // Include required Quill CSS if not included elsewhere
                            if (!document.getElementById('quill-css')) {
                                const link = document.createElement('link');
                                link.id = 'quill-css';
                                link.rel = 'stylesheet';
                                link.href = 'https://cdn.quilljs.com/1.3.7/quill.snow.css';
                                document.head.appendChild(link);
                            }
                            
                            // Include required Quill JS if not included elsewhere
                            if (typeof Quill === 'undefined') {
                                const script = document.createElement('script');
                                script.src = 'https://cdn.quilljs.com/1.3.7/quill.min.js';
                                script.onload = () => this.setupEditor();
                                document.head.appendChild(script);
                            } else {
                                this.setupEditor();
                            }
                        },
                        setupEditor() {
                            // Quill configuration
                            const toolbarOptions = [
                                ['bold', 'italic', 'underline', 'strike'],
                                ['blockquote', 'code-block'],
                                [{ 'header': 1 }, { 'header': 2 }],
                                [{ 'list': 'ordered'}, { 'list': 'bullet' }],
                                [{ 'script': 'sub'}, { 'script': 'super' }],
                                [{ 'size': ['small', false, 'large', 'huge'] }],
                                [{ 'header': [1, 2, 3, 4, 5, 6, false] }],
                                [{ 'color': [] }, { 'background': [] }],
                                [{ 'align': [] }],
                                ['image', 'link'],
                                ['clean']
                            ];
                            
                            // Initialize Quill
                            this.editorInstance = new Quill('#editor', {
                                modules: {
                                    toolbar: toolbarOptions
                                },
                                theme: 'snow'
                            });
                            
                            // Set initial content
                            this.editorInstance.root.innerHTML = @js($editorContent);
                            
                            // Mark as initialized to prevent re-initialization
                            this.editorInitialized = true;
                            
                            // Update Livewire property when content changes
                            this.editorInstance.on('text-change', () => {
                                @this.set('editorContent', this.editorInstance.root.innerHTML);
                            });
                            
                            // Setup image upload handler
                            const toolbar = this.editorInstance.getModule('toolbar');
                            toolbar.addHandler('image', () => {
                                const input = document.createElement('input');
                                input.setAttribute('type', 'file');
                                input.setAttribute('accept', 'image/*');
                                input.click();
                                
                                input.onchange = async () => {
                                    const file = input.files[0];
                                    if (file) {
                                        try {
                                            const reader = new FileReader();
                                            reader.onload = async (e) => {
                                                const imageData = e.target.result;
                                                const range = this.editorInstance.getSelection();
                                                
                                                // Upload via Livewire
                                                const response = await @this.uploadEditorImage(imageData);
                                                
                                                if (response && response.url) {
                                                    // Insert into editor
                                                    this.editorInstance.insertEmbed(range.index, 'image', response.url);
                                                } else {
                                                    console.error('Upload failed', response?.error || 'Unknown error');
                                                    alert('Image upload failed. Please try again.');
                                                }
                                            };
                                            reader.readAsDataURL(file);
                                        } catch (error) {
                                            console.error('Error uploading image:', error);
                                            alert('Image upload failed. Please try again.');
                                        }
                                    }
                                };
                            });
                        }
                    }" x-init="$nextTick(() => initEditor())">
                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700">Content</label>
                            <div wire:ignore>
                                <div id="editor" class="h-64 mt-1 bg-white border border-gray-300 rounded-md"></div>
                            </div>
                            <div class="flex items-center mt-2 text-sm text-gray-500">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 mr-1" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd" />
                                </svg>
                                <span>Use the toolbar to format text, add code blocks, and insert images</span>
                            </div>
                        </div>
                        @error('blocks')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="date" class="block text-sm font-medium text-gray-700">Date</label>
                        <input type="date" wire:model="date" id="date"
                            @class([
                                'block w-full mt-1 rounded-md shadow-sm sm:text-sm',
                                'border-red-300 focus:border-red-500 focus:ring-red-500' => $errors->has('date'),
                                'border-gray-300 focus:border-indigo-500 focus:ring-indigo-500' => !$errors->has('date')
                            ])>
                        @error('date')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="tags" class="block text-sm font-medium text-gray-700">Tags</label>
                        <div class="space-y-2">
                            <input type="text" 
                                wire:model="tagInput" 
                                wire:keydown.enter.prevent="addTagFromInput($event.keyCode)"
                                wire:keydown.tab="addTagFromInput($event.keyCode)" 
                                id="tags"
                                class="block w-full mt-1 border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                                placeholder="Add tags (press Enter or Tab to add)">
                            
                            <div class="flex flex-wrap gap-2">
                                @foreach($tags as $index => $tag)
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-sm font-medium bg-indigo-100 text-indigo-800">
                                    {{ $tag }}
                                    <button type="button" wire:click="removeTag({{ $index }})" class="ml-1.5 inline-flex text-indigo-600 hover:text-indigo-800">
                                        <span class="sr-only">Remove tag</span>
                                        &times;
                                    </button>
                                </span>
                                @endforeach
                            </div>
                        </div>
                        @error('tags')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700">Useful Links</label>
                        <div class="mt-4 space-y-4">
                            <!-- Add new link form -->
                            <div class="flex gap-3">
                                <div class="flex-1">
                                    <input type="url" wire:model="newLink.url" 
                                        class="block w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                                        placeholder="https://">
                                    @error('newLink.url') 
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                                <div class="flex-1">
                                    <input type="text" wire:model="newLink.caption" 
                                        class="block w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                                        placeholder="What is this link about?">
                                    @error('newLink.caption') 
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                                <button type="button" wire:click="addLink"
                                    class="inline-flex items-center px-3 py-2 text-sm font-medium text-white bg-indigo-600 border border-transparent rounded-md shadow-sm hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2">
                                    Add Link
                                </button>
                            </div>

                            <!-- List of added links -->
                            @if(count($shared_links) > 0)
                                <div class="mt-3 space-y-3">
                                    @foreach($shared_links as $index => $link)
                                        <div class="flex items-center gap-2 p-3 rounded-lg bg-gray-50 group">
                                            <div class="flex-1">
                                                <h4 class="text-sm font-medium text-gray-900">{{ $link['caption'] }}</h4>
                                                <a href="{{ $link['url'] }}" target="_blank" rel="noopener noreferrer" 
                                                    class="text-sm text-indigo-600 hover:text-indigo-900">
                                                    {{ $link['url'] }}
                                                </a>
                                            </div>
                                            <button type="button" wire:click="removeLink({{ $index }})"
                                                class="p-1 text-gray-400 hover:text-red-500">
                                                <span class="sr-only">Remove link</span>
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                                </svg>
                                            </button>
                                        </div>
                                    @endforeach
                                </div>
                            @endif
                        </div>
                    </div>

                    <div class="mt-4">
                        <label class="inline-flex items-center">
                            <input type="checkbox" wire:model="is_private" class="text-blue-600 border-gray-300 rounded shadow-sm form-checkbox focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                            <span class="ml-2 text-gray-700">Make this entry private</span>
                        </label>
                        <p class="mt-1 text-sm text-gray-500">When private, this entry can only be accessed by you</p>
                    </div>

                    <div class="flex justify-end space-x-3">
                        <a href="{{ route('challenges.detail', ['challengeId' => $challengeId]) }}"
                            class="inline-flex justify-center px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-md shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2">
                            Cancel
                        </a>
                        <button type="submit"
                            class="inline-flex justify-center px-4 py-2 text-sm font-medium text-white bg-indigo-600 border border-transparent rounded-md shadow-sm hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2">
                            Save Entry
                        </button>
                    </div>
