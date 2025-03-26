<?php

namespace App\Livewire\LinkWizard;

use Illuminate\View\View;
use Spatie\LivewireWizard\Components\StepComponent;

class SecondStep extends StepComponent
{
    public function render() : View
    {
        return view('livewire.link-wizard.second-step');
    }
}
