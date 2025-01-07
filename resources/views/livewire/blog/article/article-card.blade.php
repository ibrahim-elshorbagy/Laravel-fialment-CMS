<section class="flex flex-col justify-center min-h-screen mx-auto sm:px-6">
    <!-- Header Section -->
    <div class="flex flex-wrap items-center justify-between mb-8">
        <h2 class="mr-10 text-4xl font-bold leading-none md:text-5xl">
            Latest Articles
        </h2>
        <a href="#"
            class="block pb-1 mt-2 text-base font-black text-blue-600 uppercase border-b border-transparent hover:border-blue-600">
            Go to insights ->
        </a>
    </div>

    <!-- Articles Grid -->
    <div class="flex flex-wrap ">
        @foreach ($this->articles as $article)
        <div class="w-full max-w-full px-4 mb-8 sm:w-1/2 lg:w-1/3">
            <!-- Card Container -->
            <div class="flex flex-col h-full overflow-hidden bg-white border border-gray-400 group">
                <!-- Article Image -->
                <img src="{{ Storage::url($article?->image?->path) }}" alt="{{ $article?->image?->alt_text }}"
                    class="object-cover object-center w-full h-[12rem] md:h-[24rem] duration-700 ease-out transition group-hover:scale-105" />

                <!-- Card Content -->
                <div class="flex flex-col flex-grow px-4 py-6">
                    <!-- Main Content Area -->
                    <div class="flex-grow">
                        <!-- Article Title (Large) -->
                        <a href="{{ route('article.show', $article->slug) }}"
                            class="block mb-4 text-2xl font-black leading-tight hover:underline hover:text-blue-600">
                            {{ $article->title }}
                        </a>

                        <!-- Article Excerpt -->
                        @if ($article->content)
                        <p class="mb-4">
                            {!! Str::limit($article->brief ?? '', 100) !!}
                        @endif
                    </div>

                    <div class="flex items-center mt-auto gap-x-3">
                        <x-filament::avatar
                            :src="$article?->user?->avatar_url ? Storage::url($article?->user?->avatar_url) : 'https://ui-avatars.com/api/?name=' . urlencode(Auth::user()?->name)"
                            :alt="Auth::user()?->name" />
                        <div>
                            <h5 class="text-sm text-gray-800 dark:text-neutral-200">By {{ $article?->user?->name }}</h5>
                        </div>
                    </div>

                    <!-- Read More Link -->
                    <div>
                        <a href="{{ route('article.show', $article->slug) }}" wire:navigate
                            class="inline-block pb-1 mt-2 text-base font-black text-blue-600 uppercase border-b border-transparent hover:border-blue-600">
                            Read More ->
                        </a>
                    </div>


                </div>
            </div>
        </div>
        @endforeach
    </div>
</section>
