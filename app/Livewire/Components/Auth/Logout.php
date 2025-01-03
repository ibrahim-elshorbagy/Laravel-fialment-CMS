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
            <button wire:click="logout"
                class="flex items-center gap-2 px-2 py-1.5 text-sm font-medium text-neutral-600 underline-offset-2 hover:bg-black/5 hover:text-neutral-900 focus-visible:underline focus:outline-none dark:text-neutral-300 dark:hover:bg-white/5 dark:hover:text-white"
                role="menuitem">
                <i class="fa-solid fa-right-from-bracket"></i>
                <span>Sign Out</span>
            </button>
        HTML;
    }
}
