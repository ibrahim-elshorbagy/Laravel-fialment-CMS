@props(['article'])
<article {{ $attributes->merge(['class' => ' [&:not(:last-child)]:border-b border-neutral-300 dark:border-neutral-700
    pb-10 dark:bg-neutral-900 dark:text-neutral-100']) }}>

    <div class="grid items-start grid-cols-12 gap-3 mt-5 article-body">
        <div class="flex items-center col-span-12 md:col-span-4 article-thumbnail">
            <a wire:navigate href="{{route('article.show',$article->slug)}}">
                <img class="mx-auto md:mx-0 md:mw-100 rounded-xl" src="{{ $article->getThumbnailUrl() }}"
                    alt="thumbnail">
            </a>
        </div>
        <div class="col-span-8">
            <div class="flex items-center py-1 text-sm article-meta">
                <x-filament::avatar :src="$article->user?->getAvatarUrl()" :alt="$article->user->name"
                    class="w-8 h-8 sm:w-12 sm:h-12" />

                <span class="text-xs text-neutral-500 dark:text-neutral-400">{{
                    optional($article->published_at)->format('d M Y') }}</span>
            </div>
            <h2 class="text-xl font-bold text-neutral-900 dark:text-neutral-100">
                <a wire:navigate href="{{route('article.show',$article->slug)}}">
                    {{ $article->title }}
                </a>
            </h2>

            <p class="mt-2 text-base font-light text-neutral-700 dark:text-neutral-300">
                {{ $article->brief }}
            </p>
            <div class="flex items-center justify-between mt-6 article-actions-bar">
                <div class="flex flex-wrap gap-x-2">
                    <x-classification.category-badge :categories="$article->categories" />
                </div>
                {{-- <div class="flex items-center space-x-4">
                    <span class="text-sm text-neutral-500 dark:text-neutral-400">{{ $article->getReadingTime() }} min
                        read</span>
                    <livewire:like-button :key="'like-' . $article->id" :$article />
                </div> --}}
            </div>
        </div>
    </div>
</article>
