<div>
    @if (count($chunks))
        <div class="px-6 mt-8">
            @for($chunk = 0; $chunk < $page; $chunk++)
                <div class="border-b border-gray-100 dark:border-gray-900 last:border-b-0"
                     wire:key="chunks-{{ $chunk }}">
                    <livewire:comment-chunk :markdownOptions="$markdownOptions"
                                            :allowGuests="$allowGuests"
                                            :maxDepth="$maxDepth"
                                            :emojis="$emojis"
                                            :ids="$chunks[$chunk]"
                                            wire:key="chunk-{{ md5(json_encode($this->chunks[$chunk])) }}"/>
                </div>
            @endfor
        </div>
    @endif


    @if ($this->hasMorePages())
        <div class="flex items-center justify-center mt-8">
            <button
                class="inline-flex items-center px-4 py-2 text-xs font-semibold tracking-widest text-gray-700 uppercase transition duration-150 ease-in-out bg-white border border-gray-300 rounded-md shadow-sm dark:bg-gray-800 dark:border-gray-600 dark:text-gray-200 hover:bg-gray-50 dark:hover:bg-gray-800 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 disabled:opacity-25"
                wire:click="loadMore">
                Load more
            </button>
        </div>
    @endif
    @if ($this->allowGuests || auth()->user())
        <form wire:submit="createComment" class="mt-4">
            <div class="mb-3">
                <x-markdown-editor :options="$markdownOptions" wire:model="form.body" placeholder="Post a comment"
                                   class="w-full" rows="4"/>

                @error('form.body')
                <p class="mt-1 mb-1 text-red-500 dark:text-red-400">{{ $message }}</p>
                @enderror
            </div>
            <button
                type="submit"
                wire:loading.attr="disabled"
                wire:loading.class="opacity-50 cursor-not-allowed"
                class="inline-flex items-center px-4 py-2 text-xs font-semibold tracking-widest text-white uppercase transition duration-150 ease-in-out bg-gray-800 border border-transparent rounded-md dark:bg-gray-900 dark:text-gray-100 hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 disabled:opacity-50">
                Post a Comment
            </button>
        </form>
    @endif
</div>
