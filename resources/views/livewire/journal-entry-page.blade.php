@php
    use Illuminate\Support\Facades\Storage;
@endphp
<div class="py-12">
    <div
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
                                    <div class="absolute right-2 top-2 flex space-x-1 opacity-0 group-hover:opacity-100 transition-opacity">
                                        @if($index > 0)
                                            <button type="button" wire:click="moveBlockUp({{ $index }})"
                                                class="p-1 text-gray-500 hover:text-gray-700 rounded">
                                                ↑
                                            </button>
                                        @endif
                                        @if($index < count($blocks) - 1)
                                            <button type="button" wire:click="moveBlockDown({{ $index }})"
                                                class="p-1 text-gray-500 hover:text-gray-700 rounded">
                                                ↓
                                            </button>
                                        @endif
                                        <button type="button" wire:click="removeBlock({{ $index }})"
                                            class="p-1 text-red-500 hover:text-red-700 rounded">
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
                                                class="rounded-md border-gray-300">
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
                                                        class="max-w-full h-auto rounded-lg"
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
                        <label for="shared_link" class="block text-sm font-medium text-gray-700">Shared Link (Optional)</label>
                        <input type="url" wire:model="shared_link" id="shared_link"
                            class="block w-full mt-1 border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                            placeholder="https://">
                        @error('shared_link')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
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

