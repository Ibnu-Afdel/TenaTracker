<div class="py-6">
    @if ($notFound)
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6">
                <div class="text-center">
                    <h3 class="text-lg font-medium text-gray-900">Challenge Not Found</h3>
                    <p class="mt-1 text-sm text-gray-500">
                        The challenge you're looking for either doesn't exist or is not publicly shared.
                    </p>
                </div>
            </div>
        </div>
    @else
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                <div class="p-6 sm:px-20 bg-white">
                    <div class="mt-4">
                        <div class="flex justify-between items-center">
                            <h2 class="text-2xl font-semibold text-gray-800">{{ $challenge->name }}</h2>
                            <div class="flex items-center space-x-4">
                                <button wire:click="toggleFavorite" class="text-gray-400 hover:text-yellow-500">
                                    <svg class="w-6 h-6 {{ $challenge->favorite ? 'text-yellow-500' : '' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z" />
                                    </svg>
                                </button>
                                <span class="px-2 py-1 text-xs rounded-full 
                                    @if($challenge->status === 'completed') bg-green-100 text-green-800
                                    @elseif($challenge->status === 'ongoing') bg-blue-100 text-blue-800
                                    @else bg-yellow-100 text-yellow-800 @endif">
                                    {{ ucfirst($challenge->status) }}
                                </span>
                            </div>
                        </div>
                        
                        <div class="mt-4 text-gray-600">
                            {{ $challenge->description }}
                        </div>

                        @if($challenge->tags->count() > 0)
                            <div class="mt-4 flex flex-wrap gap-2">
                                @foreach($challenge->tags as $tag)
                                    <span class="px-2 py-1 bg-gray-100 text-gray-600 text-sm rounded">
                                        {{ $tag->name }}
                                    </span>
                                @endforeach
                            </div>
                        @endif

                        <div class="mt-6">
                            <h3 class="text-lg font-medium text-gray-900">Journal Entries</h3>
                            <div class="mt-4 space-y-4">
                                @forelse($challenge->journalEntries as $entry)
                                    <div class="border-l-4 border-blue-500 pl-4 py-2">
                                        <div class="text-sm text-gray-500">
                                            {{ $entry->created_at->format('M d, Y') }}
                                        </div>
                                        <div class="mt-1">{{ $entry->content }}</div>
                                    </div>
                                @empty
                                    <p class="text-gray-500">No journal entries yet.</p>
                                @endforelse
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>

