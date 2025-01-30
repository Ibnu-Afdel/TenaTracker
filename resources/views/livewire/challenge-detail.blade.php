<div class="p-6">
    <h2 class="text-xl font-bold">{{ $challenge->name }}</h2>
    <p class="text-gray-600">{{ $challenge->description }}</p>
    <p class="text-sm text-gray-500">Start: {{ $challenge->start_date }} | End: {{ $challenge->end_date }}</p>
    
    <!-- Tags -->
    <div class="mt-2">
        @foreach($challenge->tags as $tag)
            <span class="px-2 py-1 text-sm text-blue-800 bg-blue-100 rounded-full">{{ $tag->name }}</span>
        @endforeach
    </div>

    <!-- Status & Actions -->
    <div class="flex items-center mt-4 space-x-4">
        <button wire:click="toggleStatus" class="px-3 py-1 rounded text-white 
                {{ $challenge->status === 'Ongoing' ? 'bg-green-500' : ($challenge->status === 'Completed' ? 'bg-blue-500' : 'bg-gray-500') }}">
            {{ $challenge->status }}
        </button>

        <button wire:click="$set('confirmingDelete', true)" class="px-3 py-1 text-white bg-red-500 rounded">
            Delete Challenge
        </button>
    </div>

    <!-- Delete Confirmation Modal -->
    @if($confirmingDelete)
    <div class="fixed inset-0 flex items-center justify-center bg-gray-900 bg-opacity-50">
        <div class="p-6 bg-white rounded-lg">
            <p class="text-lg">Are you sure you want to delete this challenge?</p>
            <div class="flex justify-between mt-4">
                <button wire:click="$set('confirmingDelete', false)" class="px-4 py-2 text-white bg-gray-500 rounded">Cancel</button>
                <button wire:click="deleteChallenge" class="px-4 py-2 text-white bg-red-500 rounded">Delete</button>
            </div>
        </div>
    </div>
    @endif

    <!-- Journal Entries Section -->
    <div class="mt-6">
        <h3 class="text-lg font-bold">Journal Entries</h3>
        <button wire:click="$emit('openJournalEntryForm')" class="px-3 py-1 text-white bg-blue-500 rounded">+ Add Entry</button>

        <div wire:loading class="text-center text-gray-500">Loading...</div>

        @foreach($journalEntries as $entry)
            <div class="p-4 mt-2 border rounded">
                <p class="text-gray-800">{{ $entry->content }}</p>
                <p class="text-xs text-gray-500">Date: {{ $entry->date }}</p>
                <a href="{{ route('journal.view', $entry->id) }}" class="text-sm text-blue-500">View</a>
            </div>
        @endforeach

        <!-- Pagination -->
        <div class="mt-4">
            {{ $journalEntries->links() }}
        </div>
    </div>
</div>
