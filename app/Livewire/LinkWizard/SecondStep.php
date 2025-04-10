<?php

namespace App\Livewire\LinkWizard;

use Illuminate\View\View;
use Spatie\LivewireWizard\Components\StepComponent;

class SecondStep extends StepComponent
{
    public function stepInfo() : array
    {
        return [
            'label' => 'Details',
        ];
    }

    public function render() : View
    {
        return view('livewire.link-wizard.second-step');
    }
}
