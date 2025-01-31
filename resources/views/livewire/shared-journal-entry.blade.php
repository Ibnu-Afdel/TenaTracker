<div class="py-8">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        @if($entry)
            <div class="bg-white overflow-hidden shadow-xl rounded-xl">
                <!-- Header with refined gradient -->
                <div class="bg-gradient-to-r from-purple-600 via-purple-500 to-indigo-600 px-6 py-8">
                    <div class="max-w-3xl mx-auto">
                        <h1 class="text-2xl md:text-3xl font-bold text-white leading-tight">
                            @if($entry->challenge)
                                {{ $entry->challenge->title }}
                            @else
                                Journal Entry
                            @endif
                        </h1>
                        <div class="mt-3 flex items-center text-purple-100">
                            <svg class="w-5 h-5 mr-2 opacity-75" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                            </svg>
                            <span class="text-sm md:text-base">{{ $entry->date->format('F j, Y') }}</span>
                        </div>
                    </div>
                </div>

                <!-- Content Section with better spacing -->
                <div class="px-4 sm:px-6 lg:px-8 py-6 space-y-6">
                    @foreach($entry->blocks as $block)
                        <div>
                            @if($block['type'] === 'text')
                                <div class="prose prose-lg prose-purple py-2">
                                    {!! nl2br(e($block['content'])) !!}
                                </div>
                            @elseif($block['type'] === 'code')
                                <div class="bg-gray-900 rounded-xl overflow-hidden shadow-lg inline-block max-w-[50%]">
                                    <div class="px-4 py-2 bg-gray-800 border-b border-gray-700 flex items-center">
                                        <div class="flex space-x-2">
                                            <div class="w-3 h-3 rounded-full bg-red-500"></div>
                                            <div class="w-3 h-3 rounded-full bg-yellow-500"></div>
                                            <div class="w-3 h-3 rounded-full bg-green-500"></div>
                                        </div>
                                        <div class="ml-4 text-xs text-gray-400">
                                            {{ $block['metadata']['language'] ?? 'Code Snippet' }}
                                        </div>
                                    </div>
                                    <div class="p-6 overflow-x-auto">
                                        <pre class="text-gray-300"><code>{{ $block['content'] }}</code></pre>
                                    </div>
                                </div>
                            @elseif($block['type'] === 'image')
                                <div>
                                    <div class="max-w-[30%]">
                                        <img src="{{ asset('storage/' . $block['content']) }}" 
                                            alt="{{ $block['metadata']['alt'] ?? 'Journal Entry Image' }}" 
                                            class="rounded-xl shadow-lg w-full object-cover h-auto max-h-[150px]"
                                    </div>
                                </div>
                            </div>
                            @endif
                        </div>
                    @endforeach
                </div>

                <!-- Tags Section -->
                @if($entry->tags)
                    <div class="border-t border-gray-100 px-6 py-4">
                        <div class="flex flex-wrap gap-2">
                            @foreach($entry->tags as $tag)
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-purple-100 text-purple-800">
                                    #{{ $tag }}
                                </span>
                            @endforeach
                        </div>
                    </div>
                @endif
            
                <!-- Footer with subtle design -->
                <div class="border-t border-gray-200 bg-gray-50 px-6 py-4 rounded-b-xl">
                    <div class="flex items-center justify-between">
                        <div class="text-sm text-gray-600">
                            Shared via TenaTracker
                        </div>
                        <div class="text-purple-600">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M15 8a3 3 0 10-2.977-2.63l-4.94 2.47a3 3 0 100 4.319l4.94 2.47a3 3 0 10.895-1.789l-4.94-2.47a3.027 3.027 0 000-.74l4.94-2.47C13.456 7.68 14.19 8 15 8z" />
                            </svg>
                        </div>
                    </div>
                </div>
            </div>
        @else
            <div class="bg-white rounded-xl shadow-xl p-8 text-center">
                <div class="w-16 h-16 mx-auto">
                    <svg class="w-full h-full text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
                <h3 class="mt-4 text-lg font-medium text-gray-900">Entry Not Found</h3>
                <p class="mt-2 text-gray-500">
                    This journal entry is not available or has been removed.
                </p>
            </div>
        @endif
    </div>
</div>
