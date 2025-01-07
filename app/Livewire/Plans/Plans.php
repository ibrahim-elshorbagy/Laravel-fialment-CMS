<?php

namespace App\Livewire\Plans;

use Livewire\Component;

class Plans extends Component
{
    public function render()
    {
        return view('livewire.plans.plans')->layout('layouts.app');
    }
}
