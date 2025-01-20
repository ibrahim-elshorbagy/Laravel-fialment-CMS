
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}"
    x-data="{ theme: localStorage.getItem('theme') || 'light' }"
    x-init="$watch('theme', val => localStorage.setItem('theme', val))" x-bind:class="{ 'dark': theme === 'dark' }">

<head>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles

</head>
<body class="font-sans antialiased dark:bg-neutral-950">
        <article class="flex flex-col gap-4 px-2 py-6 m-5 mx-auto mt-16 m sm:gap-8 sm:py-12 max-w-7xl">
        <!-- Hero Section with Image -->
        <section class="bg-gradient-to-r from-blue-50 to-purple-50 dark:from-neutral-800 dark:to-neutral-900">
            @if ($article->media)
            <img src="{{ $article->getThumbnailUrl() }}" alt="{{ $article->media?->alt }}"
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
                <x-filament::avatar :src="$article->user?->getAvatarUrl()" :alt="$article->user->name"
                    class="w-8 h-8 sm:w-12 sm:h-12" />
                <div class="flex flex-col">
                    <span class="text-base font-semibold text-blue-600 sm:text-lg dark:text-blue-400">
                        {{ $article->user->name ?? 'Unknown Author' }}
                    </span>
                    <span class="text-xs sm:text-sm text-neutral-600 dark:text-neutral-300">
                        {{ optional($article->created_at)->format('F j, Y') }}
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


    </article>

        @livewireScripts
    </body>

</html>

