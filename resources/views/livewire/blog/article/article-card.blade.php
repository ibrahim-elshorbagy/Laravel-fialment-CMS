<section class="flex flex-col justify-center min-h-screen mx-auto sm:px-6">
    <!-- Header Section -->
    <div class="flex flex-wrap items-center justify-between mb-8">
        <h2 class="mr-10 text-4xl font-bold leading-none text-neutral-800 md:text-5xl dark:text-neutral-100">
            Latest Articles
        </h2>
        <a href="#"
            class="block pb-1 mt-2 text-base font-black text-blue-600 uppercase border-b border-transparent dark:text-blue-400 hover:border-blue-600 dark:hover:border-blue-400">
            Go to insights ->
        </a>
    </div>

    <!-- Articles Grid -->
    <div class="flex flex-wrap">
        @foreach ($this->articles as $article)
        <div class="w-full max-w-full px-4 mb-8 sm:w-1/2 lg:w-1/3">
            <!-- Card Container -->
            <div
                class="flex flex-col h-full overflow-hidden bg-white border border-gray-400 group dark:bg-neutral-900 dark:border-neutral-700">
                <!-- Article Image -->
                <img src="{{ Storage::url($article?->image?->path) }}" alt="{{ $article?->image?->alt_text }}"
                    class="object-cover object-center w-full h-[12rem] md:h-[24rem] duration-700 ease-out transition group-hover:scale-105" />

                <!-- Card Content -->
                <div class="flex flex-col flex-grow px-4 py-6">
                    <!-- Main Content Area -->
                    <div class="flex-grow">
                        <!-- Article Title -->
                        <a href="{{ route('article.show', $article->slug) }}"
                            class="block mb-4 text-2xl font-black leading-tight text-neutral-800 dark:text-neutral-100 hover:text-blue-600 dark:hover:text-blue-400 hover:underline">
                            {{ $article->title }}
                        </a>

                        <!-- Article Excerpt -->
                        @if ($article->content)
                        <p class="mb-4 text-neutral-600 dark:text-neutral-300">
                            {!! Str::limit($article->brief ?? '', 100) !!}
                        </p>
                        @endif
                    </div>

                    <div class="flex items-center mt-auto gap-x-3">
                        <x-filament::avatar
                            :src="$article?->user?->avatar_url ? Storage::url($article?->user?->avatar_url) : 'https://ui-avatars.com/api/?name=' . urlencode(Auth::user()?->name)"
                            :alt="Auth::user()?->name" />
                        <div>
                            <h5 class="text-sm text-neutral-800 dark:text-neutral-200">By {{ $article?->user?->name }}
                            </h5>
                        </div>
                    </div>

                    <!-- Read More Link -->
                    <div>
                        <a href="{{ route('article.show', $article->slug) }}" wire:navigate
                            class="inline-block pb-1 mt-2 text-base font-black text-blue-600 uppercase border-b border-transparent dark:text-blue-400 hover:border-blue-600 dark:hover:border-blue-400">
                            Read More ->
                        </a>
                    </div>
                </div>
            </div>
        </div>
        @endforeach
    </div>
</section>
