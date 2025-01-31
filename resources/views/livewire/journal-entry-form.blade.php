<div>
    <!-- Journal Entry Modal -->
    @if($showJournalModal)
    <div class="fixed inset-0 flex items-center justify-center bg-gray-900 bg-opacity-50">
        <div class="w-1/3 p-6 bg-white rounded-lg shadow-lg">
            <h2 class="mb-4 text-xl font-bold">New Journal Entry</h2>

            <form wire:submit="saveJournalEntry">
                <!-- Content Input -->
                <div class="mb-4">
                    <label class="block font-semibold">Content</label>
                    <textarea 
                        wire:model.live="content" 
                        class="w-full p-2 border rounded @error('content') border-red-500 @enderror">
                    </textarea>
                    @error('content') <p class="text-sm text-red-500">{{ $message }}</p> @enderror
                </div>

                <!-- Date Input -->
                <div class="mb-4">
                    <label class="block font-semibold">Date</label>
                    <input 
                        type="date" 
                        wire:model.live="date" 
                        class="w-full p-2 border rounded @error('date') border-red-500 @enderror">
                    @error('date') <p class="text-sm text-red-500">{{ $message }}</p> @enderror
                </div>

                                                <!-- Code Snippet Input -->
                                                <div class="mb-4">
                                                    <label class="block font-semibold">Code Snippet</label>
                                                    <textarea 
                                                        wire:model.live="code_snippet" 
                                                        class="w-full p-2 border rounded font-mono @error('code_snippet') border-red-500 @enderror">
                                                    </textarea>
                                                    @error('code_snippet') <p class="text-sm text-red-500">{{ $message }}</p> @enderror
                                                </div>

                                                <!-- Links Section -->
                                                <div class="mb-4">
                                                    <label class="block font-semibold">Links</label>
                                                    <div class="space-y-2">
                                                        @foreach($links as $index => $link)
                                                            <div class="flex items-center space-x-2">
                                                                <span>{{ $link['url'] }}</span>
                                                                <span>{{ $link['caption'] }}</span>
                                                                <button type="button" wire:click="removeLink({{ $index }})" class="text-red-500">Remove</button>
                                                            </div>
                                                        @endforeach
                                                    </div>
                                                    <div class="flex mt-2 space-x-2">
                                                        <input type="url" wire:model="newUrl" placeholder="URL" class="w-1/2 p-2 border rounded">
                                                        <input type="text" wire:model="newCaption" placeholder="Caption" class="w-1/2 p-2 border rounded">
                                                        <button type="button" wire:click="addLink" class="p-2 text-white bg-blue-500 rounded">Add Link</button>
                                                    </div>
                                                    @error('links.*.url') <p class="text-sm text-red-500">{{ $message }}</p> @enderror
                                                    @error('links.*.caption') <p class="text-sm text-red-500">{{ $message }}</p> @enderror
                                                </div>
                <!-- Image Upload -->
                <div class="mb-4">
                    <label class="block font-semibold">Upload Image</label>
                    <div wire:loading.remove wire:target="image">
                        <input type="file" wire:model="image" class="p-2 border rounded">
                    </div>
                    <div wire:loading wire:target="image" class="text-sm text-gray-500">
                        Uploading...
                    </div>
                    @if ($image)
                        <p class="text-sm text-green-500">Image selected.</p>
                    @endif
                    @error('image') <p class="text-sm text-red-500">{{ $message }}</p> @enderror
                </div>

                <!-- Public Toggle -->
                <div class="flex items-center mb-4">
                    <input type="checkbox" wire:model="is_public" class="mr-2">
                    <label>Make Public</label>
                </div>

                <!-- Submit & Close -->
                <button type="button" wire:click="closeJournalEntryForm" class="p-2 text-white bg-gray-500 rounded hover:bg-gray-600" wire:loading.attr="disabled">
                    <span wire:loading.remove>Cancel</span>
                    <span wire:loading>Closing...</span>
                </button>
                    <button type="submit" class="p-2 text-white bg-blue-500 rounded hover:bg-blue-600" wire:loading.attr="disabled">
                        <span wire:loading.remove wire:target="saveJournalEntry">Save</span>
                        <span wire:loading wire:target="saveJournalEntry">Saving...</span>
                    </button>
                </div>
            </form>
        </div>
    </div>
    @endif
</div>
