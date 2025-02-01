<div>
    @if ($showForm)
        <!-- Modal Backdrop -->
        <div class="fixed inset-0 z-50 transition-opacity duration-300 ease-in-out bg-gray-900/75"
            x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0"
            x-transition:enter-end="opacity-100" x-transition:leave="ease-in duration-200"
            x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0">
            <!-- Modal Container -->
            <div class="fixed inset-0 z-50 overflow-y-auto">
                <div class="flex items-end justify-center min-h-full p-4 sm:items-center sm:p-0">
                    <div class="relative px-4 pt-5 pb-4 overflow-hidden text-left transition-all transform bg-white rounded-lg shadow-xl sm:my-8 sm:w-full sm:max-w-2xl sm:p-6"
                        x-transition:enter="ease-out duration-300"
                        x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                        x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                        x-transition:leave="ease-in duration-200"
                        x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                        x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95">

                        <!-- Modal Header -->
                        <div class="absolute top-0 right-0 hidden pt-4 pr-4 sm:block">
                            <button type="button" wire:click="closeModal"
                                class="text-gray-400 bg-white rounded-md hover:text-gray-500 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2">
                                <span class="sr-only">Close</span>
                                <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                                    stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                                </svg>
                            </button>
                        </div>

                        <div class="sm:flex sm:items-start">
                            <div class="w-full mt-3 text-center sm:mt-0 sm:text-left">
                                <h3 class="text-xl font-semibold leading-6 text-gray-900">
                                    {{ $isEditing ? 'Edit Challenge' : 'Create New Challenge' }}</h3>

                                <form wire:submit="save" class="mt-6 space-y-6">
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700">Challenge Name</label>
                                        <div class="mt-1">
                                            <input type="text" wire:model="name"
                                                class="block w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                                                placeholder="Enter challenge name">
                                            @error('name')
                                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                            @enderror
                                        </div>
                                    </div>

                                    <div>
                                        <label class="block text-sm font-medium text-gray-700">Description</label>
                                        <div class="mt-1">
                                            <textarea wire:model="description" rows="4"
                                                class="block w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                                                placeholder="Enter challenge description"></textarea>
                                            @error('description')
                                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700">Start Date</label>
                                            <div class="mt-1">
                                                <input type="date" wire:model="start_date"
                                                    class="block w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                                                @error('start_date')
                                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                                @enderror
                                            </div>
                                        </div>
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700">End Date</label>
                                            <div class="mt-1">
                                                <input type="date" wire:model="end_date"
                                                    class="block w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                                                @error('end_date')
                                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>




                                    <!-- Tags -->
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700">Tags</label>
                                        <div class="mt-1">
                                            <div class="flex gap-2 mb-2">
                                                @foreach ($tags as $index => $tag)
                                                    <span
                                                        class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                                        {{ $tag }}
                                                        <button type="button"
                                                            wire:click="removeTag({{ $index }})"
                                                            class="ml-1 inline-flex items-center p-0.5 text-blue-400 hover:bg-blue-200 hover:text-blue-500 rounded-full">
                                                            <svg class="w-3 h-3" fill="none" stroke="currentColor"
                                                                viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                                    stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                                            </svg>
                                                        </button>
                                                    </span>
                                                @endforeach
                                            </div>
                                            <input type="text" wire:model="tagInput"
                                                wire:keydown.enter.prevent="addTagFromInput"
                                                wire:keydown.tab.prevent="addTagFromInput"
                                                placeholder="Add a tag (tap tab or enter to add)..."
                                                class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6">
                                        </div>
                                    </div>


                                    <div class="relative flex items-start">
                                        <div class="flex items-center h-6">
                                            <input type="checkbox" wire:model="is_favorite"
                                                class="w-4 h-4 text-indigo-600 border-gray-300 rounded focus:ring-indigo-500">
                                        </div>
                                        <div class="ml-3 text-sm leading-6">
                                            <label class="font-medium text-gray-700">Mark as Favorite</label>
                                        </div>
                                    </div>

                                    <div class="flex justify-end mt-6 gap-x-4">
                                        <button type="button" wire:click="closeModal"
                                            class="px-3 py-2 text-sm font-semibold text-gray-900 bg-white rounded-md shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-gray-50">
                                            Cancel
                                        </button>
                                        <button type="submit"
                                            class="px-3 py-2 text-sm font-semibold text-white bg-indigo-600 rounded-md shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600">
                                            {{ $isEditing ? 'Update Challenge' : 'Create Challenge' }}
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>
