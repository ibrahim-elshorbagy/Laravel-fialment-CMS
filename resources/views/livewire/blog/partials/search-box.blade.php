<div x-data="{query: '{{ request('search', '') }}'}" x-on:keyup.enter.window="$dispatch('search',{search : query})"
    id="search-box">
    <div class="flex">
        <div class="flex">
            <div class="flex items-center px-3 py-2 mb-3 bg-gray-100 rounded-2xl dark:bg-neutral-700">
                <span>
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                        stroke="currentColor" class="w-6 h-6 text-gray-500 dark:text-neutral-400">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607z" />
                    </svg>
                </span>
                <input x-model='query'
                    class="ml-1 text-xs text-gray-800 bg-transparent border-none outline-none w-35 focus:outline-none focus:border-none focus:ring-0 placeholder:text-gray-400 dark:text-neutral-100 dark:placeholder:text-neutral-400"
                    type="text" placeholder="Search Blogo">
                <x-button x-on:click="$dispatch('search', {search:query})"
                    class="dark:bg-neutral-600 dark:text-neutral-100 dark:hover:bg-neutral-500">
                    Search
                </x-button>
            </div>
        </div>
    </div>
</div>
