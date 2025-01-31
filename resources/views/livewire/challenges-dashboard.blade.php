<div>
    <div class="py-6 mx-auto max-w-7xl sm:px-6 lg:px-8">
        <!-- Search and Filter Bar -->
        <!-- Search and Filter Bar -->
        <div class="mb-8 bg-white rounded-lg shadow">
            <div class="p-4 sm:p-6">
                <!-- Header Section -->
                <div class="flex flex-col space-y-4 sm:flex-row sm:items-center sm:justify-between sm:space-y-0 mb-6">
                    <h2 class="text-2xl font-bold text-gray-800 text-center sm:text-left">My Challenges</h2>
                    <button wire:click="$dispatch('openCreateChallengeModal')" 
                            class="px-4 py-2 font-semibold text-white transition-colors bg-blue-600 rounded-lg shadow hover:bg-blue-700 w-full sm:w-auto">
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
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                            </svg>
                        </div>
                    </div>
                </div>
                <div class="flex flex-col space-y-4 lg:flex-row lg:space-y-0 lg:space-x-4 lg:items-center">
                    <select wire:model.live="statusFilter"
                        class="w-full lg:w-48 px-4 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500">
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

            <!-- Loading Indicator -->
            <div wire:loading wire:target="search, statusFilter, toggleFavorites" 
                class="fixed inset-0 flex items-center justify-center bg-gray-900 bg-opacity-50">
                <div class="p-4 bg-white rounded-lg shadow-lg">
                    <div class="w-8 h-8 mx-auto border-b-2 border-blue-600 rounded-full animate-spin"></div>
                    <p class="mt-2 text-gray-600">Loading...</p>
                </div>
            </div>

            <!-- Challenges Grid -->
            <!-- Challenges Grid -->
            <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-3">
                @foreach($this->challenges as $challenge)
                    <div class="transition-shadow bg-white border rounded-lg shadow-sm hover:shadow-md">
                        <div class="p-5">
                            <div class="flex items-start justify-between mb-2">
                                <h3 class="text-lg font-semibold text-gray-900">
                                    <a href="{{ route('challenges.detail', $challenge->id) }}" class="hover:text-blue-600">
                                        {{ $challenge->name }}
                                    </a>
                                </h3>
                                <button wire:click="toggleFavorite({{ $challenge->id }})" class="text-gray-400 hover:text-yellow-400">
                                    <svg class="h-6 w-6 {{ $challenge->is_favorite ? 'text-yellow-400' : 'text-gray-400' }}" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                                    </svg>
                                </button>
                            </div>
                            <p class="mb-4 text-gray-600">{{ $challenge->description }}</p>
                            <div class="flex items-center justify-between">
                                <span class="px-3 py-1 text-sm rounded-full
                                    {{ $challenge->status === 'Ongoing' ? 'bg-green-100 text-green-800' : 
                                    ($challenge->status === 'Completed' ? 'bg-blue-100 text-blue-800' : 'bg-yellow-100 text-yellow-800') }}">
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
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                    </svg>
                    <h3 class="text-lg font-medium text-gray-900">No challenges found</h3>
                    <p class="mt-1 text-gray-500">Start by creating a new challenge!</p>
                </div>
            @endif
        </div>
    </div>

    <!-- Create Challenge Modal Component -->
    @livewire('create-challenge-modal')
</div>
