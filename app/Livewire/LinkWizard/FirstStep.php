<?php

namespace App\Livewire\LinkWizard;

use Illuminate\View\View;
use Spatie\LivewireWizard\Components\StepComponent;

class FirstStep extends StepComponent
{
    public function render() : View
    {
        return view('livewire.link-wizard.first-step');
    }
}
