<?php

namespace App\Livewire\Components\Auth;
use Illuminate\Support\Facades\Auth;

use Livewire\Component;

class Logout  extends Component
{
    public function logout()
    {
        Auth::logout();
        return $this->redirect('/', navigate: true);

    }
    public function render()
    {
        return <<<'HTML'
            <a wire:click="logout" wire:navigate
                class="cursor-pointer flex items-center gap-2 px-2 py-1.5 text-sm font-medium text-neutral-600 underline-offset-2 hover:bg-black/5 hover:text-neutral-900 focus-visible:underline focus:outline-none dark:text-neutral-300 dark:hover:bg-white/5 dark:hover:text-white">
                <x-heroicon-o-arrow-left-start-on-rectangle class="w-5 h-5 text-gray-500 dark:text-neutral-300" /> Sign Out
            </a>
        HTML;
    }
}
