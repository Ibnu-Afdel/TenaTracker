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
                            class="block w-full mt-1 border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                        @error('title') 
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <div class="mb-4">
                            <div class="flex space-x-2">
                                <button type="button" wire:click="addBlock('text')"
                                    class="inline-flex items-center px-3 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-md shadow-sm hover:bg-gray-50">
                                    <span class="mr-2">+</span> Text
                                </button>
                                <button type="button" wire:click="addBlock('code')"
                                    class="inline-flex items-center px-3 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-md shadow-sm hover:bg-gray-50">
                                    <span class="mr-2">+</span> Code
                                </button>
                                <button type="button" wire:click="addBlock('image')"
                                    class="inline-flex items-center px-3 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-md shadow-sm hover:bg-gray-50">
                                    <span class="mr-2">+</span> Image
                                </button>
                            </div>
                        </div>

                        <div class="space-y-4">
                            @foreach($blocks as $index => $block)
                                <div class="relative p-4 border rounded-lg group">
                                    <div class="absolute flex space-x-1 transition-opacity opacity-0 right-2 top-2 group-hover:opacity-100">
                                        @if($index > 0)
                                            <button type="button" wire:click="moveBlockUp({{ $index }})"
                                                class="p-1 text-gray-500 rounded hover:text-gray-700">
                                                ↑
                                            </button>
                                        @endif
                                        @if($index < count($blocks) - 1)
                                            <button type="button" wire:click="moveBlockDown({{ $index }})"
                                                class="p-1 text-gray-500 rounded hover:text-gray-700">
                                                ↓
                                            </button>
                                        @endif
                                        <button type="button" wire:click="removeBlock({{ $index }})"
                                            class="p-1 text-red-500 rounded hover:text-red-700">
                                            ×
                                        </button>
                                    </div>

                                    @if($block['type'] === 'text')
                                        <textarea wire:model="blocks.{{ $index }}.content" rows="3"
                                            class="block w-full mt-1 border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                                            placeholder="Write your content here..."></textarea>
                                    @elseif($block['type'] === 'code')
                                        <div class="space-y-2">
                                            <select wire:model="blocks.{{ $index }}.metadata.language" 
                                                class="border-gray-300 rounded-md">
                                                <option value="bash">Bash</option>
                                                <option value="php">PHP</option>
                                                <option value="javascript">JavaScript</option>
                                                <option value="python">Python</option>
                                                <option value="html">HTML</option>
                                                <option value="css">CSS</option>
                                            </select>
                                            <textarea wire:model="blocks.{{ $index }}.content" rows="4"
                                                class="block w-full font-mono border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                                                placeholder="Paste your code here..."></textarea>
                                        </div>
                                    @elseif($block['type'] === 'image')
                                        <div>
                                            <div class="relative">
                                                <input type="file" wire:model="blocks.{{ $index }}.upload"
                                                    class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-medium file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100"
                                                    accept="image/*">
                                                    
                                                <div wire:loading wire:target="blocks.{{ $index }}.upload" 
                                                    class="absolute inset-0 flex items-center justify-center bg-gray-100 bg-opacity-50">
                                                    <div class="inline-flex items-center px-4 py-2 font-semibold text-gray-700">
                                                        <svg class="w-4 h-4 mr-2 animate-spin" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                                        </svg>
                                                        Uploading...
                                                    </div>
                                                </div>
                                            </div>

                                            @error("blocks.{$index}.upload") 
                                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                            @enderror

                                            @if(isset($block['content']))
                                                <div class="mt-2">
                                                    @php
                                                        $imageUrl = $block['content'] instanceof \Livewire\Features\SupportFileUploads\TemporaryUploadedFile 
                                                            ? $block['content']->temporaryUrl() 
                                                            : Storage::disk('public')->url($block['content']);
                                                    @endphp
                                                    <img src="{{ $imageUrl }}" 
                                                        class="h-auto max-w-full rounded-lg"
                                                        alt="Uploaded image">
                                                </div>
                                            @endif
                                        </div>
                                    @endif
                                </div>
                            @endforeach
                        </div>
                    </div>

                    <div>
                        <label for="date" class="block text-sm font-medium text-gray-700">Date</label>
                        <input type="date" wire:model="date" id="date"
                            class="block w-full mt-1 border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                        @error('date')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>


                    <div>
                        <label for="tags" class="block text-sm font-medium text-gray-700">Tags</label>
                        <div class="space-y-2">
                            <input type="text" wire:model="tagInput" wire:keydown="addTagFromInput($event.keyCode)" id="tags"
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
                </form>
            </div>
        </div>
    </div>
</div>

