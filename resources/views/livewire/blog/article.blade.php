<article class="flex flex-col gap-5 py-8 mx-auto max-w-7xl">
    <div
        class="flex flex-col p-6 border rounded-md group border-neutral-300 bg-neutral-50 text-neutral-600 dark:border-neutral-700 dark:bg-neutral-900 dark:text-neutral-300">

        <!-- Title Image -->
        <div>
            @if ($article->media_id)
                <img src="{{ Storage::url($article?->image?->path) }}" alt="{{ $article->title }}"
                    class="object-cover w-full mb-4 rounded-md shadow-lg h-[48rem]">
                @else
            <div class="flex items-center justify-center w-full h-48 mb-4 bg-gray-200 rounded-md shadow-lg">
                <span class="text-sm text-gray-500">No Image Available</span>
            </div>
            @endif
        </div>

        <!-- Title -->
        <h1 class="text-2xl font-bold text-neutral-800 dark:text-neutral-200">
            {{ $article->title }}
        </h1>

        <!-- Article Metadata -->
        <div class="mt-2 text-sm text-neutral-500 dark:text-neutral-400">
            Written by <span class="font-semibold">{{ $article->user->name ?? 'Unknown Author' }}</span>
            on {{ $article->created_at }}
        </div>

        <!-- Content -->
        <p class="mt-6 text-base leading-relaxed text-neutral-600 dark:text-neutral-300">
            {!! $article->content !!}
        </p>
    </div>

    <div class="flex flex-col p-6 border rounded-md group border-neutral-300 bg-neutral-50 text-neutral-600 dark:border-neutral-700 dark:bg-neutral-900 dark:text-neutral-300">
        <livewire:comments :model="$article" :emojis="['👍', '👎', '❤️', '😂', '😯', '😢', '😡']" />
    </div>
</article>
