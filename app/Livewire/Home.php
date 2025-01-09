<?php

namespace App\Livewire;

use Livewire\Component;
use RalphJSmit\Laravel\SEO\Support\SEOData;

class Home extends Component
{



    public function render()
    {
        return view('livewire.home')
                    ->layout('layouts.app',
                                [
                                    'SEOData' => new SEOData(
                                        title: 'Home',
                                        description: 'Your meta description here',
                                        type: 'website'
                                    ),
                                'keywords'=> ['keyword1', 'keyword2', 'keyword3'],

                                ]
                            );
            }
}
