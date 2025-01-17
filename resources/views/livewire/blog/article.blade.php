<article class="flex flex-col gap-4 px-2 py-6 m-5 mx-auto mt-16 m sm:gap-8 sm:py-12 max-w-7xl">
    <!-- Hero Section with Image -->
    <section class="bg-gradient-to-r from-blue-50 to-purple-50 dark:from-neutral-800 dark:to-neutral-900">
        @if ($article->media)
        <img src="{{ Storage::url($article?->media?->path) }}" alt="{{ $article->media?->alt }}"
            class="object-cover w-full h-[24rem] sm:h-[48rem] rounded-lg">
        @else
        <div class="flex items-center justify-center w-full h-32 sm:h-48">
            <span class="text-xs sm:text-sm text-neutral-500 dark:text-neutral-400">No Image Available</span>
        </div>
        @endif
    </section>

    <!-- Article Content Section -->
    <section class="px-3 py-4 bg-white rounded-lg sm:px-6 lg:px-8 sm:py-8 dark:bg-neutral-900">
        <!-- Title -->
        <h1 class="mb-4 text-2xl font-bold sm:mb-6 sm:text-4xl text-neutral-800 dark:text-neutral-100">
            {{ $article->title }}
        </h1>

        <!-- Author Info -->
        <div class="flex items-center gap-2 mb-4 sm:gap-4 sm:mb-8">
            <x-filament::avatar
                :src="$article?->user?->avatar_url ? Storage::url($article?->user?->avatar_url) : 'https://ui-avatars.com/api/?name=' . urlencode(Auth::user()?->name)"
                :alt="Auth::user()?->name" class="w-8 h-8 sm:w-12 sm:h-12" />
            <div class="flex flex-col">
                <span class="text-base font-semibold text-blue-600 sm:text-lg dark:text-blue-400">
                    {{ $article->user->name ?? 'Unknown Author' }}
                </span>
                <span class="text-xs sm:text-sm text-neutral-600 dark:text-neutral-300">
                    {{ $article->created_at->format('F j, Y') }}
                </span>
            </div>
        </div>

        <!-- Categories & Tags -->
        <div class="flex flex-wrap gap-3 mb-4 sm:gap-6 sm:mb-8">
            <div class="flex items-center gap-1 sm:gap-2">
                <span class="text-xs font-semibold sm:text-sm text-neutral-800 dark:text-neutral-100">Categories:</span>
                @if($article->categories->isNotEmpty())
                    <x-classification.category-badge :categories="$article->categories" />
                @else
                    <span class="text-xs sm:text-sm text-neutral-500 dark:text-neutral-400">No Categories</span>
                @endif
            </div>
            <div class="flex items-center gap-1 sm:gap-2">
                <span class="text-xs font-semibold sm:text-sm text-neutral-800 dark:text-neutral-100">Tags:</span>
                @if($article->tags->isNotEmpty())
                    <x-classification.tag-badge :tags="$article->tags" />
                @else
                    <span class="text-xs sm:text-sm text-neutral-500 dark:text-neutral-400">No Tags</span>
                @endif
            </div>
        </div>

        <!-- Content -->
        <div class="prose-sm prose sm:prose max-w-none text-neutral-600 dark:text-neutral-300">
            {!! $article->content !!}
        </div>
    </section>

    <!-- Comments Section -->
    <section class="px-3 py-4 rounded-lg sm:px-6 lg:px-8 sm:py-8 bg-neutral-50 dark:bg-neutral-800">
        <h2 class="mb-4 text-xl font-bold sm:mb-8 sm:text-3xl text-neutral-800 dark:text-neutral-100">Comments</h2>
        <livewire:comments :model="$article" :emojis="['👍', '👎', '❤️', '😂', '😯', '😢', '😡']" />
    </section>
</article>

@push('scripts')
    <script type="application/ld+json">
        {
            "@context": "https://schema.org",
            "@type": "Article",
            "headline": "{{ $article->title ?? '' }}",
            "author": "{{ $article->user?->name ?? '' }}",
            "datePublished": "{{ $article->published_at ? (new DateTime($article->published_at))->format('Y-m-d') : '' }}",
            "image": "{{ $article->image ? asset('images/' . $article->image) : '' }}"
        }
    </script>
@endpush

