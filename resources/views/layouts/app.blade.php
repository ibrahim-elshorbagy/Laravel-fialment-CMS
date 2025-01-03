<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}"
    x-data="{ theme: localStorage.getItem('theme') || 'light' }"
    x-init="$watch('theme', val => localStorage.setItem('theme', val))" x-bind:class="{ 'dark': theme === 'dark' }">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'LivewireSaaS') }}</title>

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
            <nav class="sticky top-0 z-10 flex items-center justify-between px-4 py-2 border-b border-neutral-300 bg-neutral-50 dark:border-neutral-700 dark:bg-neutral-900"
                aria-label="top navibation bar">

                <!-- sidebar toggle button for small screens  -->

                <a href="{{ route('welcome') }}" class="w-12 text-neutral-600 dark:text-neutral-300" wire:navigate>
                    <x-application-logo />
                </a>

                <!-- Profile Menu  -->
                <div class="flex items-center gap-5">
                    <x-theme-toggle />
                    <div x-data="{ userDropdownIsOpen: false }" class="relative"
                        x-on:keydown.esc.window="userDropdownIsOpen = false">

                        @auth
                        <button type="button"
                            class="flex items-center w-full gap-2 p-2 text-left rounded-md cursor-pointer text-neutral-600 hover:bg-black/5 hover:text-neutral-900 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-black dark:text-neutral-300 dark:hover:bg-white/5 dark:hover:text-white dark:focus-visible:outline-white"
                            x-bind:class="userDropdownIsOpen ? 'bg-black/10 dark:bg-white/10' : ''" aria-haspopup="true"
                            x-on:click="userDropdownIsOpen = ! userDropdownIsOpen"
                            x-bind:aria-expanded="userDropdownIsOpen">

                            <img src="{{ Storage::url(Auth::user()?->avatar_url) }}"
                                class="object-cover rounded-md size-8" alt="avatar" aria-hidden="true" />

                            <div class="flex-col hidden md:flex">
                                <span class="text-sm font-bold text-neutral-900 dark:text-white">{{
                                    Auth::user()?->name}}</span>
                                <span class="text-xs text-neutral-500 dark:text-neutral-400">{{
                                    Auth::user()?->email}}</span>
                                <span class="sr-only">profile settings</span>
                            </div>
                        </button>



                        <!-- menu -->
                        <div x-cloak x-show="userDropdownIsOpen"
                            class="absolute right-0 z-20 w-48 bg-white border divide-y rounded-md top-14 h-fit divide-neutral-300 border-neutral-300 dark:divide-neutral-700 dark:border-neutral-700 dark:bg-neutral-950"
                            role="menu" x-on:click.outside="userDropdownIsOpen = false"
                            x-on:keydown.down.prevent="$focus.wrap().next()"
                            x-on:keydown.up.prevent="$focus.wrap().previous()" x-transition=""
                            x-trap="userDropdownIsOpen">

                            <div class="flex flex-col py-1.5">
                                <a href="{{ route('filament.dashboard.pages.my-profile') }}"
                                    class="flex items-center gap-2 px-2 py-1.5 text-sm font-medium text-neutral-600 underline-offset-2 hover:bg-black/5 hover:text-neutral-900 focus-visible:underline focus:outline-none dark:text-neutral-300 dark:hover:bg-white/5 dark:hover:text-white"
                                    role="menuitem">
                                    <i class="fa-regular fa-user"></i>
                                    <span>Profile</span>
                                </a>
                            </div>

                            @auth
                            <div class="flex flex-col">
                                <livewire:components.auth.logout />
                            </div>
                            @endauth
                        </div>
                        @endauth

                        @guest
                        <button type="button"
                            class="flex items-center w-full gap-2 p-2 text-left rounded-md cursor-pointer text-neutral-600 hover:bg-black/5 hover:text-neutral-900 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-black dark:text-neutral-300 dark:hover:bg-white/5 dark:hover:text-white dark:focus-visible:outline-white"
                            x-bind:class="userDropdownIsOpen ? 'bg-black/10 dark:bg-white/10' : ''" aria-haspopup="true"
                            x-on:click="userDropdownIsOpen = ! userDropdownIsOpen"
                            x-bind:aria-expanded="userDropdownIsOpen">
                            <i class="fa-solid fa-user"></i>

                        </button>



                        <!-- menu -->
                        <div x-cloak x-show="userDropdownIsOpen"
                            class="absolute right-0 z-20 w-48 bg-white border divide-y rounded-md top-14 h-fit divide-neutral-300 border-neutral-300 dark:divide-neutral-700 dark:border-neutral-700 dark:bg-neutral-950"
                            role="menu" x-on:click.outside="userDropdownIsOpen = false"
                            x-on:keydown.down.prevent="$focus.wrap().next()"
                            x-on:keydown.up.prevent="$focus.wrap().previous()" x-transition=""
                            x-trap="userDropdownIsOpen">

                            <div class="flex flex-col py-1.5">
                                <a href="{{ route('filament.dashboard.auth.login') }}" wire:navigate
                                    class="flex items-center gap-2 px-2 py-1.5 text-sm font-medium text-neutral-600 underline-offset-2 hover:bg-black/5 hover:text-neutral-900 focus-visible:underline focus:outline-none dark:text-neutral-300 dark:hover:bg-white/5 dark:hover:text-white"
                                    role="menuitem">
                                    <i class="fa-solid fa-right-to-bracket"></i>
                                    <span>Login</span>
                                </a>
                                <a href="{{ route('filament.dashboard.auth.register') }}" wire:navigate
                                    class="flex items-center gap-2 px-2 py-1.5 text-sm font-medium text-neutral-600 underline-offset-2 hover:bg-black/5 hover:text-neutral-900 focus-visible:underline focus:outline-none dark:text-neutral-300 dark:hover:bg-white/5 dark:hover:text-white"
                                    role="menuitem">
                                    <i class="fa-solid fa-plus"></i>
                                    <span>register</span>
                                </a>
                            </div>


                            @auth
                            <div class="flex flex-col">
                                <livewire:pages.auth.logout />
                            </div>
                            @endauth
                        </div>
                        @endguest
                    </div>
                </div>
            </nav>
            <!-- main content  -->
            <div id="main-content" class="p-4">
                <div class="overflow-y-auto">
                    {{ $slot }}
                </div>
            </div>
        </div>
    </div>





    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    @livewireScripts
</body>

</html>
