<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}"
    x-data="{ theme: localStorage.getItem('theme') || 'light' }"
    x-init="$watch('theme', val => localStorage.setItem('theme', val))" x-bind:class="{ 'dark': theme === 'dark' }">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

        @php

        $seoData = null;
        $keywords = null;

        if (Route::currentRouteName() == 'article.show') {
            $article = Route::current()->parameter('article');
            $seoData = $article;
            $keywords = $article->seo?->keywords ? json_decode($article->seo->keywords, true) : null;
        } elseif (isset($SEOData)) {
            $seoData = $SEOData;
            $keywords = $SEOData->keywords ?? null;
        }
        @endphp

        {!! $seoData ? seo()->for($seoData) : seo() !!}

        @if($keywords)
            <meta name="keywords" content="{{ is_array($keywords) ? implode(', ', $keywords) : $keywords }}">
        @endif

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    {{-- Font Awesome --}}
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css"
        integrity="sha512-Evv84Mr4kqVGRNSgIGL/F/aIDqQb7xQ2vcrdIwxfjThSH8CSR7PBEakCr51Ck+w+/U6swU2Im1vVX0SVk9ABhg=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    @livewireStyles
</head>

<body class="font-sans antialiased">


    <div x-data="{ sidebarIsOpen: false }" class="relative flex flex-col w-full md:flex-row ">
        <!-- This allows screen readers to skip the sidebar and go directly to the main content. -->
        <a class="sr-only" href="#main-content">skip to the main content</a>

        <!-- dark overlay for when the sidebar is open on smaller screens  -->
        <div x-cloak x-show="sidebarIsOpen" class="fixed inset-0 z-20 bg-neutral-950/10 backdrop-blur-sm md:hidden"
            aria-hidden="true" x-on:click="sidebarIsOpen = false" x-transition.opacity></div>

        <!-- top navbar & main content  -->
        <div class="w-full overflow-y-auto bg-white h-svh dark:bg-neutral-950">

            <!-- top navbar  -->

            <nav x-data="{ mobileMenuIsOpen: false }" @click.away="mobileMenuIsOpen = false"
                class="fixed z-50 flex items-center justify-between w-full px-6 py-4 border-b bg-neutral-50 border-neutral-300 dark:border-neutral-700 dark:bg-neutral-900"
                >
                <!-- Logo -->
                <a href="{{ route('welcome') }}" class="w-12 text-neutral-600 dark:text-neutral-300" wire:navigate>
                    <x-application-logo />
                </a>
                <!-- Desktop Menu -->
                <ul class="items-center hidden gap-4 sm:flex">
                    <li><a href="#"
                            class="font-bold text-black underline-offset-2 hover:text-black focus:outline-none focus:underline dark:text-white dark:hover:text-white"
                            aria-current="page">Products</a></li>
                    <li><a href="{{ route('plans') }}" wire:navigate
                            class="font-medium text-neutral-600 underline-offset-2 hover:text-black focus:outline-none focus:underline dark:text-neutral-300 dark:hover:text-white">Plans</a>
                    </li>
                    <li><a href="#"
                            class="font-medium text-neutral-600 underline-offset-2 hover:text-black focus:outline-none focus:underline dark:text-neutral-300 dark:hover:text-white">Blog</a>
                    </li>
                    <!-- User Pic -->
                    <x-theme-toggle />
                    <li x-data="{ userDropDownIsOpen: false, openWithKeyboard: false }"
                        @keydown.esc.window="userDropDownIsOpen = false, openWithKeyboard = false"
                        class="relative flex items-center">
                        <button @click="userDropDownIsOpen = ! userDropDownIsOpen" :aria-expanded="userDropDownIsOpen"
                            @keydown.space.prevent="openWithKeyboard = true" @keydown.enter.prevent="openWithKeyboard = true"
                            @keydown.down.prevent="openWithKeyboard = true"
                            class="rounded-full focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-black dark:focus-visible:outline-white"
                            aria-controls="userMenu">

                        @auth
                       <x-filament::avatar :src="Auth::user()?->avatar_url ? Storage::url(Auth::user()->avatar_url) : 'https://ui-avatars.com/api/?name=' . urlencode(Auth::user()?->name)" :alt="Auth::user()?->name" />
                        @endauth

                        @guest
                        <x-heroicon-o-user-circle class="w-6 h-6 text-gray-500 dark:text-neutral-300" />
                        @endguest
                        </button>
                        <!-- User Dropdown -->
                        <ul x-cloak x-show="userDropDownIsOpen || openWithKeyboard" x-transition.opacity x-trap="openWithKeyboard"
                            @click.outside="userDropDownIsOpen = false, openWithKeyboard = false"
                            @keydown.down.prevent="$focus.wrap().next()" @keydown.up.prevent="$focus.wrap().previous()"
                            id="userMenu"
                            class="absolute right-0 top-12 flex w-full min-w-[12rem] flex-col overflow-hidden rounded-md border border-neutral-300 bg-neutral-50  dark:border-neutral-700 dark:bg-neutral-900">

                        @auth
                            <li class="border-b border-neutral-300 dark:border-neutral-700">
                                <div class="flex items-center gap-2 px-4 py-2">
                                    <x-heroicon-o-user class="w-6 h-6 text-gray-500 dark:text-neutral-300" />
                                    <div class="flex flex-col">
                                        <span class="text-sm font-medium text-neutral-900 dark:text-white">{{Auth::user()?->name}}</span>
                                        <p class="text-xs text-neutral-600 dark:text-neutral-300">{{Auth::user()?->email}}</p>
                                    </div>

                                </div>
                            </li>

                            <li class="flex flex-col py-1.5">
                                <a href="{{ route('filament.dashboard.pages.my-profile') }}"
                                    class="flex items-center gap-2 px-2 py-1.5 text-sm font-medium text-neutral-600 underline-offset-2 hover:bg-black/5 hover:text-neutral-900 focus-visible:underline focus:outline-none dark:text-neutral-300 dark:hover:bg-white/5 dark:hover:text-white">
                                    <x-heroicon-o-user-circle class="w-5 h-5 text-gray-500 dark:text-neutral-300" />Profile</a>
                            </li>

                            <li><a href="{{ route('filament.dashboard.pages.dashboard') }}"
                                    class="flex items-center gap-2 px-2 py-1.5 text-sm font-medium text-neutral-600 underline-offset-2 hover:bg-black/5 hover:text-neutral-900 focus-visible:underline focus:outline-none dark:text-neutral-300 dark:hover:bg-white/5 dark:hover:text-white">
                                    <x-heroicon-o-home class="w-5 h-5 text-gray-500 dark:text-neutral-300" />Dashboard</a>
                            </li>

                            <li class="border-t border-neutral-300 dark:border-neutral-700">
                                <livewire:components.auth.logout />
                            </li>
                        @endauth
                        @guest
                            <div class="flex flex-col py-1.5">
                                <a href="{{ route('filament.dashboard.auth.login') }}" wire:navigate
                                    class="flex items-center gap-2 px-2 py-1.5 text-sm font-medium text-neutral-600 underline-offset-2 hover:bg-black/5 hover:text-neutral-900 focus-visible:underline focus:outline-none dark:text-neutral-300 dark:hover:bg-white/5 dark:hover:text-white">
                                    <x-heroicon-o-user class="w-6 h-6 text-gray-500 dark:text-neutral-300" />
                                    <span>Login</span>
                                </a>
                                <a href="{{ route('filament.dashboard.auth.register') }}" wire:navigate
                                    class="flex items-center gap-2 px-2 py-1.5 text-sm font-medium text-neutral-600 underline-offset-2 hover:bg-black/5 hover:text-neutral-900 focus-visible:underline focus:outline-none dark:text-neutral-300 dark:hover:bg-white/5 dark:hover:text-white">
                                    <x-heroicon-o-plus class="w-6 h-6 text-gray-500 dark:text-neutral-300" />
                                    <span>Register</span>
                                </a>
                            </div>
                        @endguest

                        </ul>
                    </li>
                </ul>
                <!-- Mobile Menu Button -->
                <button @click="mobileMenuIsOpen = !mobileMenuIsOpen" :aria-expanded="mobileMenuIsOpen"
                    :class="mobileMenuIsOpen ? 'fixed top-6 right-6 z-20' : null" type="button"
                    class="flex text-neutral-600 dark:text-neutral-300 sm:hidden" aria-label="mobile menu"
                    aria-controls="mobileMenu">
                    <svg x-cloak x-show="!mobileMenuIsOpen" xmlns="http://www.w3.org/2000/svg" fill="none" aria-hidden="true"
                        viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="size-6">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25h16.5" />
                    </svg>
                    <svg x-cloak x-show="mobileMenuIsOpen" xmlns="http://www.w3.org/2000/svg" fill="none" aria-hidden="true"
                        viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="size-6">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12" />
                    </svg>
                </button>
                <!-- Mobile Menu -->
                <ul x-cloak x-show="mobileMenuIsOpen"
                    x-transition:enter="transition motion-reduce:transition-none ease-out duration-300"
                    x-transition:enter-start="-translate-y-full" x-transition:enter-end="translate-y-0"
                    x-transition:leave="transition motion-reduce:transition-none ease-out duration-300"
                    x-transition:leave-start="translate-y-0" x-transition:leave-end="-translate-y-full"
                    class="fixed inset-x-0 top-0 z-10 flex flex-col px-4 pt-10 pb-6 overflow-y-auto border-b max-h-svh rounded-b-md border-neutral-300 bg-neutral-50 dark:border-neutral-700 dark:bg-neutral-900 sm:hidden">
                    @auth
                    <li class="mb-4 border-none">
                        <div class="flex items-center gap-2 py-2">
                        <x-filament::avatar
                        :src="Auth::user()?->avatar_url ? Storage::url(Auth::user()->avatar_url) : 'https://ui-avatars.com/api/?name=' . urlencode(Auth::user()?->name)"
                        :alt="Auth::user()?->name" />

                            <div>
                                <span class="font-medium text-neutral-900 dark:text-white">{{Auth::user()?->name}}</span>
                                <p class="text-sm text-neutral-600 dark:text-neutral-300">{{Auth::user()?->email}}</p>
                            </div>
                        </div>
                    </li>
                    @endauth
                    <li class="p-2"><a href="#" class="w-full text-lg font-bold text-black focus:underline dark:text-white"
                            aria-current="page">Products</a></li>
                    <li class="p-2"><a href="{{ route('plans') }}" wire:navigate
                            class="w-full text-lg font-medium text-neutral-600 focus:underline dark:text-neutral-300">Plans</a>
                    </li>
                    <li class="p-2"><a href="#"
                            class="w-full text-lg font-medium text-neutral-600 focus:underline dark:text-neutral-300">Blog</a></li>
                    <hr role="none" class="my-2 border-outline dark:border-neutral-700">
                    <li class="p-2">
                        <a href="{{ route('filament.dashboard.pages.my-profile') }}"
                            class="flex items-center w-full gap-2 text-neutral-600 focus:underline dark:text-neutral-300">
                            <x-heroicon-o-user-circle class="w-5 h-5 text-gray-500 dark:text-neutral-300" />Profile
                        </a></li>
                    <li class="p-2"><a href="{{ route('filament.dashboard.pages.dashboard') }}" class="flex items-center w-full gap-2 text-neutral-600 focus:underline dark:text-neutral-300">
                        <x-heroicon-o-home class="w-5 h-5 text-gray-500 dark:text-neutral-300" />Dashboard</a>
                    </li>

                    <!-- CTA Button -->
                    @guest
                    <hr role="none" class="my-2 border-outline dark:border-neutral-700">
                    <div class="flex justify-center gap-5 my-4">
                        <li ><a href="{{ route('filament.dashboard.auth.login') }}" class="px-4 py-2 text-sm font-medium tracking-wide text-center transition bg-transparent border rounded-md cursor-pointer border-neutral-600 text-neutral-600 hover:opacity-75 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-neutral-300 active:opacity-100 active:outline-offset-0 disabled:opacity-75 disabled:cursor-not-allowed dark:border-neutral-300 dark:text-neutral-300 dark:focus-visible:outline-neutral-300">Login</a></li>
                        <li ><a href="{{ route('filament.dashboard.auth.register') }}" class="px-4 py-2 text-sm font-medium tracking-wide text-center text-white transition rounded-md cursor-pointer bg-neutral-600 hover:opacity-75 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-neutral-300 active:opacity-100 active:outline-offset-0 disabled:opacity-75 disabled:cursor-not-allowed dark:bg-neutral-700 dark:text-white dark:focus-visible:outline-neutral-300">Register</a></li>
                    </div>
                    @endguest
                    @auth
                    <hr role="none" class="my-2 border-outline dark:border-neutral-700">
                        <livewire:components.auth.logout />
                    @endauth

                    <hr role="none" class=" border-outline dark:border-neutral-700">
                    <div class="inline-flex justify-center mt-2">
                        <x-theme-toggle />
                    </div>
                </ul>
            </nav>
            <!-- main content  -->
            <div id="main-content">
                <div class="overflow-y-auto">
                    {{ $slot }}
                </div>
            </div>
        </div>
    </div>





    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    @livewireScripts
    @livewireScriptConfig
    @stack('scripts')
</body>

</html>
