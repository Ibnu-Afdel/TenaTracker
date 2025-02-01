<div>
    <div class="py-6 mx-auto max-w-7xl sm:px-6 lg:px-8">
        <!-- Search and Filter Bar -->
        <div class="mb-8 bg-white rounded-lg shadow">
            <div class="p-4 sm:p-6">
                <!-- Header Section -->
                <div class="flex flex-col mb-6 space-y-4 sm:flex-row sm:items-center sm:justify-between sm:space-y-0">
                    <h2 class="text-2xl font-bold text-center text-gray-800 sm:text-left">My Challenges</h2>
                    <button wire:click="$dispatch('openCreateChallengeModal')"
                            class="w-full px-4 py-2 font-semibold text-white transition-colors bg-blue-600 rounded-lg shadow hover:bg-blue-700 sm:w-auto">
                        <svg class="inline-block w-5 h-5 mr-2 -ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                        </svg>
                        Create Challenge
                    </button>
                </div>

                <div class="flex flex-col space-y-4 lg:flex-row lg:items-center lg:space-x-6 lg:space-y-0">
                    <div class="relative lg:w-72">
                        <div class="relative">
                            <input wire:model.live="search" type="text"
                                class="w-full py-2 pl-10 pr-4 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500"
                                placeholder="Search challenges...">
                            <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                                <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                </svg>
                            </div>
                        </div>
                    </div>

                    <div class="flex flex-col space-y-4 lg:flex-row lg:space-y-0 lg:space-x-4 lg:items-center">
                        <select wire:model.live="statusFilter"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg lg:w-48 focus:ring-blue-500 focus:border-blue-500">
                            <option value="all">All Status</option>
                            <option value="Ongoing">Ongoing</option>
                            <option value="Completed">Completed</option>
                            <option value="Paused">Paused</option>
                        </select>

                        <button wire:click="toggleFavorites"
                            class="w-full lg:w-48 inline-flex items-center justify-center px-4 py-2 rounded-lg border {{ $favoritesOnly ? 'bg-yellow-100 border-yellow-400 text-yellow-800' : 'border-gray-300 bg-white text-gray-700' }} hover:bg-gray-50">
                            <svg class="h-5 w-5 {{ $favoritesOnly ? 'text-yellow-400' : 'text-gray-400' }} mr-2" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                            </svg>
                            {{ $favoritesOnly ? 'Show All' : 'Show Favorites' }}
                        </button>
                    </div>
                </div>

                <!-- Loading Spinner -->
                <div wire:loading wire:target="search, statusFilter, toggleFavorites"
                    class="fixed inset-0 flex items-center justify-center bg-gray-900 bg-opacity-50">
                    <div class="p-4 bg-white rounded-lg shadow-lg">
                        <div class="w-8 h-8 mx-auto border-b-2 border-blue-600 rounded-full animate-spin"></div>
                        <p class="mt-2 text-gray-600">Loading...</p>
                    </div>
                </div>

                <!-- Challenges Grid -->
                <div class="grid grid-cols-1 gap-6 mt-2 sm:grid-cols-2 lg:grid-cols-3">
                    @foreach($this->challenges as $challenge)
                        <div class="relative overflow-hidden transition-all duration-300 bg-white border shadow-sm rounded-xl hover:shadow-lg hover:border-blue-100 hover:ring-1 hover:ring-blue-100">
                            <div class="p-6">
                                <!-- Header -->
                                <div class="flex items-start justify-between mb-4">
                                    <h3 class="text-lg font-semibold text-gray-900">
                                        <a href="{{ route('challenges.detail', $challenge->id) }}" class="hover:text-blue-600">
                                            {{ $challenge->name }}
                                        </a>
                                    </h3>
                                    <div class="flex items-center space-x-2">
                                        <button wire:click="toggleFavorite({{ $challenge->id }})" class="text-gray-400 hover:text-yellow-400">
                                            <svg class="w-6 h-6 {{ $challenge->is_favorite ? 'text-yellow-400' : 'text-gray-400' }}" fill="currentColor" viewBox="0 0 20 20">
                                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                                            </svg>
                                        </button>
                                        <button wire:click="editChallenge({{ $challenge->id }})" class="text-gray-500 hover:text-blue-600">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/>
                                            </svg>
                                        </button>
                                        <button wire:click="confirmDelete({{ $challenge->id }})" class="text-gray-500 hover:text-red-600">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                            </svg>
                                        </button>
                                    </div>
                                </div>

                                <!-- Description -->
                                <p class="mb-4 text-sm text-gray-600 line-clamp-2">{{ $challenge->description }}</p>

                                <!-- Tags -->
                                @if($challenge->tags->isNotEmpty())
                                    <div class="flex flex-wrap gap-2 mb-4">
                                        @foreach($challenge->tags as $tag)
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-50 text-blue-700 ring-1 ring-inset ring-blue-600/20">
                                                {{ $tag->name }}
                                            </span>
                                        @endforeach
                                    </div>
                                @endif

                                <div class="flex items-center justify-between pt-4 mt-4 border-t border-gray-200">
                                    <span class="inline-flex items-center px-3 py-1 text-sm rounded-full
                                        {{ $challenge->status === 'Ongoing' ? 'bg-green-100 text-green-800' :
                                        ($challenge->status === 'Completed' ? 'bg-blue-100 text-blue-800' : 'bg-yellow-100 text-yellow-800') }}">
                                        <span class="w-2 h-2 mr-2 rounded-full
                                            {{ $challenge->status === 'Ongoing' ? 'bg-green-400' :
                                            ($challenge->status === 'Completed' ? 'bg-blue-400' : 'bg-yellow-400') }}"></span>
                                        {{ $challenge->status }}
                                    </span>
                                    <span class="text-sm text-gray-500">
                                         {{ $challenge->created_at->diffForHumans() }}
                                    </span>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                @if($this->challenges->isEmpty())
                    <div class="px-4 py-12 text-center bg-white rounded-lg shadow">
                        <svg class="w-12 h-12 mx-auto text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                        </svg>
                        <h3 class="text-lg font-medium text-gray-900">No challenges found</h3>
                        <p class="mt-1 text-gray-500">Start by creating a new challenge!</p>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Delete Confirmation Modal -->
    <div x-data="{ show: @entangle('showDeleteModal') }">
        <div x-show="show"
            class="fixed inset-0 z-50 flex items-center justify-center bg-gray-900 bg-opacity-50"
            x-transition:enter="ease-out duration-300"
            x-transition:enter-start="opacity-0"
            x-transition:enter-end="opacity-100"
            x-transition:leave="ease-in duration-200"
            x-transition:leave-start="opacity-100"
            x-transition:leave-end="opacity-0">
            <div class="w-full max-w-md p-6 mx-4 bg-white rounded-lg shadow-xl"
                x-transition:enter="ease-out duration-300"
                x-transition:enter-start="opacity-0 scale-95"
                x-transition:enter-end="opacity-100 scale-100"
                x-transition:leave="ease-in duration-200"
                x-transition:leave-start="opacity-100 scale-100"
                x-transition:leave-end="opacity-0 scale-95">
                <h3 class="mb-4 text-lg font-semibold text-gray-900">Delete Challenge</h3>
                <p class="mb-6 text-gray-600">Are you sure you want to delete this challenge? This action cannot be undone.</p>
                <div class="flex justify-end gap-4">
                    <button wire:click="cancelDelete"
                        class="px-4 py-2 text-sm font-medium text-gray-700 bg-gray-100 rounded-lg hover:bg-gray-200">
                        Cancel
                    </button>
                    <button wire:click="deleteChallenge"
                        class="px-4 py-2 text-sm font-medium text-white bg-red-600 rounded-lg hover:bg-red-700">
                        Delete Challenge
                    </button>
                </div>
            </div>
        </div>
    </div>

    

    <!-- Create Challenge Modal Component -->
    @livewire('create-challenge-modal')
</div>
