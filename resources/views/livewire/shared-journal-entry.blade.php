<div class="py-8">
    <div class="max-w-6xl px-4 mx-auto sm:px-6 lg:px-8">
        @if ($entry)
            <div class="overflow-hidden bg-white shadow-xl rounded-xl">

                <div class="px-6 py-8 bg-gradient-to-r from-purple-600 via-purple-500 to-indigo-600">
                    <div class="max-w-3xl mx-auto">
                        <h1 class="text-3xl font-bold leading-tight text-white md:text-4xl">
                            @if ($entry->challenge)
                                {{ $entry->challenge->title }}
                            @else
                                Journal Entry
                            @endif
                        </h1>
                        <div class="flex items-center mt-3 text-purple-100">
                            <svg class="w-5 h-5 mr-2 opacity-75" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                            </svg>
                            <span class="text-sm md:text-base">{{ $entry->date->format('F j, Y') }}</span>
                        </div>
                    </div>
                </div>

                <div class="px-6 pt-4 pb-2">
                    <a href="{{ route('challenges.detail', ['challengeId' => $entry->challenge->id]) }}"
                        class="inline-flex items-center gap-2 px-4 py-2.5 text-sm font-medium text-purple-700 bg-purple-50 rounded-lg hover:bg-purple-100 hover:text-purple-800 transition-colors duration-200 group focus:outline-none focus:ring-2 focus:ring-purple-500 focus:ring-offset-2">
                        <svg class="w-5 h-5 transition-transform duration-200 group-hover:-translate-x-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                        </svg>
                        Back to Challenge
                    </a>
                </div>

                <div class="px-4 py-8 space-y-8 sm:px-6 lg:px-8 lg:py-10">
                    @foreach ($entry->blocks as $block)
                        <div>
                            @if ($block['type'] === 'text')
                                <div class="py-2 prose prose-lg prose-purple">
                                    {!! nl2br(e($block['content'])) !!}
                                </div>
                            @elseif($block['type'] === 'code')
                                <div class="w-full max-w-4xl mx-auto overflow-hidden bg-gray-900 shadow-lg rounded-xl">
                                    <div class="flex items-center px-4 py-2 bg-gray-800 border-b border-gray-700">
                                        <div class="flex space-x-2">
                                            <div class="w-3 h-3 bg-red-500 rounded-full"></div>
                                            <div class="w-3 h-3 bg-yellow-500 rounded-full"></div>
                                            <div class="w-3 h-3 bg-green-500 rounded-full"></div>
                                        </div>
                                        <div class="ml-4 text-xs text-gray-400">
                                            {{ $block['metadata']['language'] ?? 'Code Snippet' }}
                                        </div>
                                    </div>
                                    <div class="p-6 overflow-x-auto">
                                        <pre class="text-gray-300"><code>{{ $block['content'] }}</code></pre>
                                    </div>
                                </div>
                            {{-- @elseif($block['type'] === 'image')
                                <div class="w-full md:max-w-[70%] mx-auto">
                                    <img src="{{ asset('storage/' . $block['content']) }}"
                                        alt="{{ $block['metadata']['alt'] ?? 'Journal Entry Image' }}"
                                        class="rounded-xl shadow-lg w-full object-cover h-auto max-h-[500px]">
                                </div>
                            @endif --}}
                            @elseif($block['type'] === 'image')
                                @php
                                    $imagePath = Str::startsWith($block['content'], 'journal-images/') 
                                        ? $block['content'] 
                                        : 'journal-images/' . $block['content'];
                                    $imageUrl = url('storage/' . $imagePath);
                                @endphp
                                <div class="relative w-full lg:max-w-[85%] xl:max-w-[90%] mx-auto mt-6 mb-8 overflow-hidden bg-gray-100 rounded-xl shadow-lg">
                                    <img 
                                        src="{{ $imageUrl }}"
                                        alt="{{ $block['metadata']['alt'] ?? 'Journal entry image' }}"
                                        class="object-contain w-full h-auto max-h-[700px] lg:max-h-[800px] opacity-0 transition-opacity duration-300 animate-fade-in"
                                        onload="this.classList.remove('opacity-0')"
                                        loading="lazy"
                                        onerror="this.onerror=null; this.parentElement.innerHTML=`
                                            <div class='p-8 text-center bg-gray-50'>
                                                <svg class='w-12 h-12 mx-auto text-gray-400' fill='none' stroke='currentColor' viewBox='0 0 24 24'>
                                                    <path stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z'></path>
                                                </svg>
                                                <p class='mt-4 text-sm text-gray-600'>Unable to load image</p>
                                                <p class='mt-2 text-xs text-gray-500'>Error loading: ${this.src}</p>
                                                <a href='${this.src}' target='_blank' class='inline-block mt-3 text-xs text-blue-600 hover:underline'>Open image in new tab</a>
                                            </div>
                                        `"
                                    >
                                </div>
                            @endif
                        </div>
                    @endforeach
                </div>

                @if ($entry->links->count() > 0)
                    <div class="border-t border-gray-100 bg-gradient-to-r from-purple-50 via-white to-purple-50">
                        <div class="px-6 py-4">
                            <div class="flex items-center gap-2 mb-4">
                                <svg class="w-5 h-5 text-purple-600" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1" />
                                </svg>
                                <h3 class="text-lg font-semibold text-gray-900">Useful Resources</h3>
                            </div>
                            <div class="grid gap-3 md:grid-cols-2">
                                @foreach ($entry->links as $link)
                                    <a href="{{ $link->url }}" target="_blank" rel="noopener noreferrer"
                                        class="p-4 transition-all duration-200 bg-white border border-purple-100 rounded-lg shadow-sm group hover:border-purple-300 hover:bg-purple-50 hover:shadow">
                                        <div class="flex items-center justify-between">
                                            <div class="flex-1 min-w-0">
                                                <h4
                                                    class="text-sm font-medium text-gray-900 truncate group-hover:text-purple-700">
                                                    {{ $link->caption }}
                                                </h4>
                                                <p class="mt-1 text-sm text-purple-600 truncate">
                                                    {{ $link->url }}
                                                </p>
                                            </div>
                                            <div
                                                class="ml-3 text-purple-400 group-hover:text-purple-600 transform group-hover:-translate-y-0.5 transition-transform duration-200">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2"
                                                        d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14" />
                                                </svg>
                                            </div>
                                        </div>
                                    </a>
                                @endforeach
                            </div>
                        </div>
                    </div>
                @endif

                @if ($entry->tags)
                    <div class="px-6 py-4 border-t border-gray-100">
                        <div class="flex flex-wrap gap-2">
                            @foreach ($entry->tags as $tag)
                                <span
                                    class="inline-flex items-center px-3 py-1 text-sm font-medium text-purple-800 bg-purple-100 rounded-full">
                                    #{{ $tag }}
                                </span>
                            @endforeach
                        </div>
                    </div>
                @endif

                <div class="px-6 py-4 border-t border-gray-200 bg-gray-50 rounded-b-xl">
                    <div class="flex items-center justify-between">
                        <div class="text-sm text-gray-600">
                            Shared via TenaTracker
                        </div>
                        <div class="text-purple-600">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                <path
                                    d="M15 8a3 3 0 10-2.977-2.63l-4.94 2.47a3 3 0 100 4.319l4.94 2.47a3 3 0 10.895-1.789l-4.94-2.47a3.027 3.027 0 000-.74l4.94-2.47C13.456 7.68 14.19 8 15 8z" />
                            </svg>
                        </div>
                    </div>
                </div>
            </div>
        @else
            <div class="p-8 text-center bg-white shadow-xl rounded-xl">
                <div class="w-16 h-16 mx-auto">
                    <svg class="w-full h-full text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
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