<div>
    <div id="journalModal" class="fixed inset-0 flex items-center justify-center hidden bg-gray-900 bg-opacity-50">
        <div class="w-1/3 p-6 bg-white rounded-lg shadow-lg">
            <h2 class="mb-4 text-xl font-bold">New Journal Entry</h2>

            <form wire:submit.prevent="saveJournalEntry">
                <!-- Content Input -->
                <div class="mb-4">
                    <label class="block font-semibold">Content</label>
                    <textarea wire:model="content" class="w-full p-2 border rounded"></textarea>
                    @error('content') <p class="text-sm text-red-500">{{ $message }}</p> @enderror
                </div>

                <!-- Date Input -->
                <div class="mb-4">
                    <label class="block font-semibold">Date</label>
                    <input type="date" wire:model="date" class="w-full p-2 border rounded">
                    @error('date') <p class="text-sm text-red-500">{{ $message }}</p> @enderror
                </div>

                <!-- Image Upload -->
                <div class="mb-4">
                    <label class="block font-semibold">Upload Image</label>
                    <input type="file" wire:model="image" class="p-2 border rounded">
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
                <div class="flex justify-between">
                    <button type="button" class="p-2 text-white bg-gray-500 rounded" onclick="closeJournalModal()">Cancel</button>
                    <button type="submit" class="p-2 text-white bg-blue-500 rounded">Save</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    function openJournalModal() {
        document.getElementById('journalModal').classList.remove('hidden');
    }
    function closeJournalModal() {
        document.getElementById('journalModal').classList.add('hidden');
    }
    window.addEventListener('openJournalModal', () => openJournalModal());
    window.addEventListener('closeJournalModal', () => closeJournalModal());
</script>
