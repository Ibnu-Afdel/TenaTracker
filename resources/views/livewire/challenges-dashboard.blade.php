<div>
    <!-- Search & Filter Section -->
    <div class="flex items-center justify-between mb-4">
        <input 
            type="text" 
            wire:model.debounce.300ms="search" 
            placeholder="Search challenges..." 
            class="w-1/3 p-2 border rounded"
        />

        <select wire:model="statusFilter" class="p-2 border rounded">
            <option value="all">All</option>
            <option value="Ongoing">Ongoing</option>
            <option value="Completed">Completed</option>
            <option value="Paused">Paused</option>
        </select>

        <button wire:click="$toggle('favoritesOnly')" class="p-2 border rounded">
            {{ $favoritesOnly ? 'Show All' : 'Show Favorites' }}
        </button>

        <button wire:click="$emit('openCreateChallengeModal')" class="p-2 text-white bg-blue-500 rounded">
            + New Challenge
        </button>
        
        @livewire('create-challenge-modal')
        
    </div>

    <!-- Loading Indicator -->
    <div wire:loading class="text-center text-gray-500">
        Loading...
    </div>

    <!-- Challenges List -->
    <div class="grid gap-4">
        @foreach($challenges as $challenge)
            <div class="p-4 border rounded shadow">
                <div class="flex justify-between">
                    <h3 class="text-lg font-semibold">{{ $challenge->name }}</h3>
                    <button wire:click="toggleFavorite({{ $challenge->id }})">
                        {{ $challenge->is_favorite ? '❤️' : '🤍' }}
                    </button>
                </div>
                <p class="text-sm text-gray-600">{{ $challenge->description }}</p>
                <p class="text-xs text-gray-500">Status: {{ $challenge->status }}</p>
                <a href="{{ route('challenges.detail', $challenge->id) }}" class="text-sm text-blue-500">View Details</a>
            </div>
        @endforeach
    </div>

    <!-- Pagination -->
    <div class="mt-4">
        {{ $challenges->links() }}
    </div>
</div>
