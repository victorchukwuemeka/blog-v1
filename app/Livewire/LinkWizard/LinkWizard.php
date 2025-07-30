<?php

namespace App\Livewire\LinkWizard;

use Spatie\LivewireWizard\Components\WizardComponent;

class LinkWizard extends WizardComponent
{
    public function steps() : array
    {
        return [
            FirstStep::class,
            SecondStep::class,
        ];
    }
}
