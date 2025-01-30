<div>
    <!-- New Challenge Button -->
    <button wire:click="$dispatch('openCreateChallengeModal')" class="px-3 py-1 text-white bg-blue-500 rounded">
        + New Challenge
    </button>

    @if($showForm)
        <!-- Modal -->
        <div class="fixed inset-0 flex items-center justify-center bg-gray-900 bg-opacity-50">
            <div class="w-1/3 p-6 bg-white rounded-lg shadow-lg">
                <h2 class="mb-4 text-xl font-bold">Create New Challenge</h2>

                <!-- Form -->
                <form wire:submit.prevent="createChallenge">
                    <div class="mb-4">
                        <label class="block font-semibold">Challenge Name</label>
                        <input type="text" wire:model="name" class="w-full p-2 border rounded">
                        @error('name') <p class="text-sm text-red-500">{{ $message }}</p> @enderror
                    </div>

                    <div class="mb-4">
                        <label class="block font-semibold">Description</label>
                        <textarea wire:model="description" class="w-full p-2 border rounded"></textarea>
                        @error('description') <p class="text-sm text-red-500">{{ $message }}</p> @enderror
                    </div>

                    <div class="flex gap-4 mb-4">
                        <div>
                            <label class="block font-semibold">Start Date</label>
                            <input type="date" wire:model="start_date" class="p-2 border rounded">
                            @error('start_date') <p class="text-sm text-red-500">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label class="block font-semibold">End Date</label>
                            <input type="date" wire:model="end_date" class="p-2 border rounded">
                            @error('end_date') <p class="text-sm text-red-500">{{ $message }}</p> @enderror
                        </div>
                    </div>

                    <div class="mb-4">
                        <label class="block font-semibold">Tags</label>
                        <select wire:model="selectedTags" multiple class="w-full p-2 border rounded">
                            @foreach ($allTags as $tag)
                                <option value="{{ $tag->id }}">{{ $tag->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="flex items-center mb-4">
                        <input type="checkbox" wire:model="is_favorite" class="mr-2">
                        <label>Mark as Favorite</label>
                    </div>

                    <div class="flex justify-between">
                        <button type="button" wire:click="closeModal" class="p-2 text-white bg-gray-500 rounded">Cancel</button>
                        <button type="submit" class="p-2 text-white bg-blue-500 rounded">Create Challenge</button>
                    </div>
                </form>
            </div>
        </div>
    @endif
</div>
