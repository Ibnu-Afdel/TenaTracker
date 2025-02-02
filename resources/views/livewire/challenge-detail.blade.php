<div>
    <!-- Loading Spinner -->
    <div wire:loading class="fixed inset-0 z-50 flex items-center justify-center bg-gray-900 bg-opacity-50">
        <div class="w-12 h-12 border-t-2 border-b-2 border-blue-500 rounded-full animate-spin"></div>
    </div>
    <div class="px-4 mx-auto max-w-7xl sm:px-6 lg:px-8">
        <div class="overflow-hidden bg-white shadow-xl sm:rounded-2xl">
            <!-- Hero Section -->
            <div class="relative overflow-hidden">
                <div class="absolute inset-0 bg-gradient-to-br from-blue-50 to-indigo-50"></div>
                <div class="relative px-6 py-10 sm:px-8 sm:py-14">
                    <a href="{{ route('dashboard') }}"
                        class="inline-flex items-center px-4 py-2 text-sm font-medium text-gray-700 transition-colors bg-gray-100 rounded-lg shadow-sm hover:bg-gray-200 hover:shadow focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2">
                        <svg class="w-5 h-5 mr-2 -ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                        </svg>
                        Back to Dashboard
                    </a>

                    <div class="flex flex-col items-start justify-between gap-8 lg:flex-row">
                        <div class="w-full space-y-6 text-center lg:w-2/3 lg:text-center">
                            <h1 class="mb-6 text-6xl font-bold tracking-tight text-gray-900">
                                {{ $this->challenge->name }}</h1>
                            <p class="mt-4 text-lg leading-relaxed text-gray-600">{{ $this->challenge->description }}
                            </p>
                            <div class="flex flex-wrap gap-2 mt-6">
                                @foreach ($this->challenge->tags as $tag)
                                    <span
                                        class="inline-flex items-center px-3 py-1 text-sm font-medium text-blue-700 transition-colors rounded-full bg-blue-50 ring-1 ring-inset ring-blue-600/20">
                                        {{ $tag->name }}
                                    </span>
                                @endforeach
                            </div>

                            <div class="flex items-center justify-center gap-4 mt-8">
                                <button wire:click="toggleStatus"
                                    class="inline-flex items-center px-4 py-2 rounded-lg text-sm font-medium transition-colors
            {{ $challenge->status === 'Ongoing'
                ? 'bg-green-100 text-green-700 hover:bg-green-200'
                : ($challenge->status === 'Completed'
                    ? 'bg-blue-100 text-blue-700 hover:bg-blue-200'
                    : 'bg-gray-100 text-gray-700 hover:bg-gray-200') }}">
                                    <span class="relative flex w-2 h-2 mr-2">
                                        <span
                                            class="animate-ping absolute inline-flex h-full w-full rounded-full opacity-75
                {{ $challenge->status === 'Ongoing' ? 'bg-green-400' : ($challenge->status === 'Completed' ? 'bg-blue-400' : 'bg-gray-400') }}">
                                        </span>
                                        <span
                                            class="relative inline-flex rounded-full h-2 w-2
                {{ $challenge->status === 'Ongoing' ? 'bg-green-500' : ($challenge->status === 'Completed' ? 'bg-blue-500' : 'bg-gray-500') }}">
                                        </span>
                                    </span>
                                    {{ $challenge->status }}
                                </button>

                                <button wire:click="confirmDeleteChallenge"
                                    class="inline-flex items-center px-4 py-2 text-sm font-medium text-red-700 transition-colors rounded-lg bg-red-50 hover:bg-red-100">
                                    <svg class="w-4 h-4 mr-2" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd"
                                            d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z"
                                            clip-rule="evenodd" />
                                    </svg>
                                    Delete Challenge
                                </button>
                            </div>
                        </div>
                        <div class="w-full mt-8 lg:w-1/3 lg:mt-0">
                            <div
                                class="grid w-full grid-cols-3 gap-6 p-6 bg-white shadow-sm rounded-xl ring-1 ring-gray-200">
                                <div class="text-center">
                                    <dt class="text-sm font-medium text-gray-500">Total Entries</dt>
                                    <dd class="mt-1 text-2xl font-semibold text-gray-900">{{ $journalEntries->total() }}
                                    </dd>
                                </div>
                                <div class="text-center">
                                    <dt class="text-sm font-medium text-gray-500">Days Active</dt>
                                    <dd class="mt-1 text-2xl font-semibold text-gray-900">
                                        {{ intval($this->challenge->created_at->diffInDays(now())) }}</dd>
                                </div>
                                <div class="text-center">
                                    <dt class="text-sm font-medium text-gray-500">Last Activity</dt>
                                    <dd class="mt-1 text-2xl font-semibold text-gray-900">
                                        {{ $journalEntries->first()?->created_at->diffForHumans() ?? 'Never' }}</dd>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>


        <!-- Delete Journal Confirmation Modal -->
        @if ($showDeleteJournalModal)
            <div class="fixed inset-0 z-50 flex items-center justify-center bg-gray-900 bg-opacity-50">
                <div class="w-full max-w-md p-6 bg-white rounded-lg shadow-xl">
                    <h2 class="text-lg font-semibold text-gray-900">Confirm Deletion</h2>
                    <p class="mt-2 text-gray-600">Are you sure you want to delete this journal entry? This action cannot be undone.</p>
                    <div class="flex justify-end gap-3 mt-6">
                        <button wire:click="cancelDeleteJournal"
                            class="px-4 py-2 text-gray-700 bg-gray-100 rounded-lg hover:bg-gray-200">
                            Cancel
                        </button>
                        <button wire:click="deleteJournalEntry"
                            class="px-4 py-2 text-white bg-red-600 rounded-lg hover:bg-red-700">
                            Delete
                        </button>
                    </div>
                </div>
            </div>
        @endif
        
        <!-- Delete Challenge Confirmation Modal -->
        @if ($confirmingDelete)
            <div class="fixed inset-0 z-50 flex items-center justify-center bg-gray-900 bg-opacity-50">
                <div class="w-full max-w-md p-6 bg-white rounded-lg shadow-xl">
                    <h2 class="text-lg font-semibold text-gray-900">Confirm Deletion</h2>
                    <p class="mt-2 text-gray-600">Are you sure you want to delete this challenge? This action cannot be undone.</p>
                    <div class="flex justify-end gap-3 mt-6">
                        <button wire:click="cancelDeleteChallenge"
                            class="px-4 py-2 text-gray-700 bg-gray-100 rounded-lg hover:bg-gray-200">
                            Cancel
                        </button>
                        <button wire:click="deleteChallenge"
                            class="px-4 py-2 text-white bg-red-600 rounded-lg hover:bg-red-700">
                            Delete
                        </button>
                    </div>
                </div>
            </div>
        @endif


        <!-- Journal Entries Section -->
        <div class="px-6 py-10 sm:px-8 sm:py-14">
            <div class="mb-8">
                <div class="flex items-center justify-between mb-6">
                    <h2 class="text-2xl font-bold text-gray-900">Journal Entries</h2>
                    <a href="{{ route('journal.create', ['challengeId' => $challenge->id]) }}"
                        class="inline-flex items-center px-4 py-2 text-sm font-medium text-white transition-colors bg-indigo-600 rounded-lg shadow hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2">
                        <svg class="w-5 h-5 mr-2" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd"
                                d="M10 5a1 1 0 011 1v3h3a1 1 0 110 2h-3v3a1 1 0 11-2 0v-3H6a1 1 0 110-2h3V6a1 1 0 011-1z"
                                clip-rule="evenodd" />
                        </svg>
                        Add New Entry
                    </a>
                </div>

                <div class="flex flex-col gap-4 md:flex-row md:items-center">
                    <div class="relative flex-1">
                        <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                            <svg class="w-5 h-5 text-gray-400" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd"
                                    d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z"
                                    clip-rule="evenodd" />
                            </svg>
                        </div>
                        <input type="text" wire:model.live="search"
                            class="w-full py-2 pl-10 pr-4 text-sm text-gray-900 bg-white border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500"
                            placeholder="Search journal entries...">
                    </div>

                    <div class="flex-shrink-0">
                        <select wire:model.live="privacyFilter"
                            class="w-full px-3 py-2 text-sm text-gray-900 bg-white border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                            <option value="all">All Entries</option>
                            <option value="public">Public Only</option>
                            <option value="private">Private Only</option>
                        </select>
                    </div>
                </div>
            </div>

            <div wire:loading class="text-center text-gray-500">Loading...</div>

            <div class="grid gap-6">
                @foreach ($journalEntries as $entry)
                    @if (!$entry->is_private || (auth()->check() && $entry->user_id === auth()->id()))
                        <div
                            class="overflow-hidden transition-shadow bg-white shadow-sm rounded-xl ring-1 ring-gray-200 hover:shadow-md">
                            <div class="p-6 space-y-4">
                                <div class="flex items-start justify-between gap-4">
                                    <div class="flex items-center gap-3">
                                        <h3 class="text-lg font-semibold text-gray-900">{{ $entry->title }}</h3>
                                        @if ($entry->is_private)
                                            <span
                                                class="inline-flex items-center px-2 py-1 text-xs font-medium rounded-full text-amber-700 bg-amber-50 ring-1 ring-inset ring-amber-600/20">
                                                <svg class="w-3 h-3 mr-1" viewBox="0 0 20 20" fill="currentColor">
                                                    <path fill-rule="evenodd"
                                                        d="M5 9V7a5 5 0 0110 0v2a2 2 0 012 2v5a2 2 0 01-2 2H5a2 2 0 01-2-2v-5a2 2 0 012-2zm8-2v2H7V7a3 3 0 016 0z"
                                                        clip-rule="evenodd" />
                                                </svg>
                                                Private
                                            </span>
                                        @else
                                            <span
                                                class="inline-flex items-center px-2 py-1 text-xs font-medium text-green-700 rounded-full bg-green-50 ring-1 ring-inset ring-green-600/20">
                                                <svg class="w-3 h-3 mr-1" viewBox="0 0 20 20" fill="currentColor">
                                                    <path fill-rule="evenodd" d="M10 12a2 2 0 100-4 2 2 0 000 4z" />
                                                    <path fill-rule="evenodd"
                                                        d="M.458 10C1.732 5.943 5.522 3 10 3s8.268 2.943 9.542 7c-1.274 4.057-5.064 7-9.542 7S1.732 14.057.458 10zM14 10a4 4 0 11-8 0 4 4 0 018 0z"
                                                        clip-rule="evenodd" />
                                                </svg>
                                                Public 
                                            </span>
                                        @endif
                                    </div>
                                    <time class="text-sm text-gray-500 tabular-nums" datetime="{{ $entry->date }}">
                                        {{ \Carbon\Carbon::parse($entry->date)->format('M j, Y') }}
                                    </time>
                                </div>

                                <!-- Display the tags for the journal -->
                                @if ($entry->tags && count($entry->tags) > 0)
                                    <div class="flex flex-wrap gap-2 mt-4">
                                        @foreach ($entry->tags as $tag)
                                            <span
                                                class="inline-flex items-center px-2 py-1 text-xs font-medium text-blue-700 rounded-full bg-blue-50 ring-1 ring-inset ring-blue-600/20">
                                                {{ $tag }}
                                            </span>
                                        @endforeach
                                    </div>
                                @endif

                            </div>

                            <div class="px-6 py-4 border-t border-gray-200 bg-gray-50">
                                <div class="flex items-center justify-between">
                                    <div class="flex items-center space-x-4">
                                        @if (!$entry->is_private && $entry->shared_link)
                                            <a href="{{ route('shared.journal', ['shareToken' => $entry->shared_link]) }}"
                                                class="inline-flex items-center text-sm font-medium text-gray-500 transition-colors hover:text-gray-900">
                                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M8.684 13.342C8.886 12.938 9 12.482 9 12c0-.482-.114-.938-.316-1.342m0 2.684a3 3 0 110-2.684m0 2.684l6.632 3.316m-6.632-6l6.632-3.316m0 0a3 3 0 105.367-2.684 3 3 0 00-5.367 2.684zm0 9.316a3 3 0 105.368 2.684 3 3 0 00-5.368-2.684z" />
                                                </svg>
                                                Share Journal (open)
                                            </a>
                                        @endif
                                        @if ($entry->is_private && $entry->user_id === auth()->id())
                                            <a href="{{ route('shared.journal', ['shareToken' => $entry->shared_link]) }}" 
                                                class="inline-flex items-center text-sm font-medium text-gray-500 transition-colors hover:text-gray-900">
                                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                                        d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8V7a4 4 0 00-8 0v4h8z" />
                                                </svg>
                                                Your's Only (open)
                                            </a>
                                        @endif
                                    </div>
                                    
                                    @if ($entry->user_id === auth()->id())
                                        <div class="flex items-center space-x-3">
                                            <a href="{{ route('journal.edit', ['entry' => $entry->id]) }}"
                                                class="inline-flex items-center px-3 py-1.5 text-sm font-medium text-indigo-700 transition-colors rounded-lg bg-indigo-50 hover:bg-indigo-100">
                                                <svg class="w-4 h-4 mr-1.5" viewBox="0 0 20 20" fill="currentColor">
                                                    <path d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z" />
                                                </svg>
                                                Edit
                                            </a>
                                            <button wire:click="confirmDeleteJournal({{ $entry->id }})"
                                                class="inline-flex items-center px-3 py-1.5 text-sm font-medium text-red-700 transition-colors rounded-lg bg-red-50 hover:bg-red-100">
                                                <svg class="w-4 h-4 mr-1.5" viewBox="0 0 20 20" fill="currentColor">
                                                    <path fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z" clip-rule="evenodd" />
                                                </svg>
                                                Delete
                                            </button>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endif
                @endforeach
            </div>

            <div class="mt-8">
                {{ $journalEntries->links() }}
            </div>
        </div>

    </div>
