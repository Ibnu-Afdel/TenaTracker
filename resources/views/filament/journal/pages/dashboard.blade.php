<x-filament::page>
    <div class="p-4 bg-white rounded-lg shadow-md dark:bg-gray-800">
        <h2 class="text-xl font-semibold mb-4">Welcome to Your Journal</h2>
        <p class="mb-4">This is your personal journal dashboard where you can manage all your entries.</p>
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mt-6">
            <div class="p-4 bg-amber-50 rounded-lg dark:bg-gray-700">
                <h3 class="text-lg font-medium mb-2">Quick Links</h3>
                <ul class="space-y-2">
                    <li>
                        <a href="{{ route('journal.create') }}" class="text-amber-600 hover:text-amber-800 dark:text-amber-400">
                            Create New Entry
                        </a>
                    </li>
                    <li>
                        <a href="#" class="text-amber-600 hover:text-amber-800 dark:text-amber-400">
                            View Recent Entries
                        </a>
                    </li>
                </ul>
            </div>
            
            <div class="p-4 bg-amber-50 rounded-lg dark:bg-gray-700">
                <h3 class="text-lg font-medium mb-2">Stats</h3>
                <p>Total Entries: {{ \App\Models\JournalEntry::count() }}</p>
                <p>Entries This Month: {{ \App\Models\JournalEntry::whereMonth('created_at', now()->month)->count() }}</p>
            </div>
        </div>
    </div>
</x-filament::page>

