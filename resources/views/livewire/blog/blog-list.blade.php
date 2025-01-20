<div class="container grid w-full grid-cols-4 px-5 mx-auto mt-32 rounded-lg d:mt-16 dark:bg-neutral-900 dark:text-neutral-100">

    <div id="side-bar"
        class="top-0 col-span-4 space-y-3 md:py-6 sm:order-last sm:col-span-1 pt-md:10 md:px-3 md:h-screen md:border-neutral-300 dark:border-neutral-700 md:md:border-t-none md:col-span-1 md:border-l dark:bg-neutral-900 dark:text-neutral-100">
        @include('livewire.blog.partials.search-box')
        <div class="border-t border-neutral-300 dark:border-neutral-700">
            <h3 class="my-3 text-lg font-semibold text-neutral-900 dark:text-neutral-100">Recommended Topics</h3>
            <div class="flex flex-wrap justify-start gap-2 topics">
                <x-classification.category-badge :categories="$categories" />
            </div>
        </div>
    </div>

    <div class="col-span-4 md:col-span-3 sm:order-first dark:bg-neutral-900 dark:text-neutral-100">
        <div class="container px-3 py-6">
            <div
                class="flex flex-wrap items-center justify-between border-b border-neutral-300 dark:border-neutral-700">

                <div class="flex gap-3 text-neutral-600 dark:text-neutral-400">

                    @if ($this->activeCategory || $search)
                    <button class="text-xs text-red-500" wire:click="ClearFilters()">
                        <svg xmlns="http://www.w3.org/2000/svg" class="inline-block w-4 h-4 align-middle" fill="none"
                            viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                    @endif

                    @if ($this->activeCategory)
                    <x-badge wire:navigate
                        href="{{ route('articles.list', ['category' => $this->activeCategory->slug]) }}"
                        :textColor="$this->activeCategory->text_color" :bgColor="$this->activeCategory->bg_color">
                        {{ $this->activeCategory->title }}
                    </x-badge>
                    @endif

                    @if ($search)
                    <span class="ml-2">
                        Searching : <strong>{{ $search }}</strong>
                    </span>
                    @endif

                </div>

                <div class="flex items-center space-x-4 font-light ">
                    {{--
                    <x-checkbox wire:model.live='popular' /> --}}
                    {{-- <x-label>Popular</x-label> --}}
                    <button
                        class="{{ $sort === 'desc' ? 'text-neutral-900 border-b border-neutral-700 dark:text-neutral-100 dark:border-neutral-500' : 'text-neutral-500 dark:text-neutral-400' }} py-4"
                        wire:click="setSort('desc')">Latest</button>
                    <button
                        class="{{ $sort === 'asc' ? 'text-neutral-900 border-b border-neutral-700 dark:text-neutral-100 dark:border-neutral-500' : 'text-neutral-500 dark:text-neutral-400' }} py-4 "
                        wire:click="setSort('asc')">Oldest</button>
                </div>
            </div>

            <div class="py-4">
                @if ($this->articles->count() > 0)
                    @foreach ($this->articles as $article)
                        <x-articles.article-item wire:key="{{$article->id}}" :article="$article" />
                    @endforeach
                @else
                    <p class="dark:text-neutral-400 text-neutral-600">
                        No articles found. Please try searching for something else.
                    </p>
                @endif
            </div>

            <div class="my-3">
                {{ $this->articles->onEachSide(1)->links() }}
            </div>
        </div>
    </div>

</div>
