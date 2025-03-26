<?php

namespace App\Livewire;

use App\Livewire\LinkWizard\FirstStep;
use App\Livewire\LinkWizard\SecondStep;
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
