<?php

namespace App\Livewire\LinkWizard;

use Spatie\LivewireWizard\Components\WizardComponent;

class LinkWizard extends WizardComponent
{
    public function mount()
    {
        if (! auth()->check()) {
            return redirect()->guest(route('login'));
        }
    }

    public function steps() : array
    {
        return [
            FirstStep::class,
            SecondStep::class,
        ];
    }
}
