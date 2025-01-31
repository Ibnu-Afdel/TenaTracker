<div class="bg-white rounded-lg shadow p-4 mb-4">
    @if($isEditing)
        <div class="mb-4">
            <textarea wire:model="content" class="w-full p-2 border rounded" rows="4"></textarea>
        </div>
        <div class="flex justify-end space-x-2">
            <button wire:click="save" class="px-4 py-2 bg-blue-500 text-white rounded hover:bg-blue-600">Save</button>
            <button wire:click="toggleEdit" class="px-4 py-2 bg-gray-300 rounded hover:bg-gray-400">Cancel</button>
        </div>
    @else
        <div class="prose max-w-none mb-4">
            {{ $content }}
        </div>
        <div class="flex justify-between items-center">
            <button wire:click="toggleEdit" class="text-blue-500 hover:text-blue-600">Edit</button>
            <a href="#" wire:click.prevent="getShareLink" class="text-blue-500 hover:text-blue-600">Shared Link</a>
        </div>
    @endif
</div>

