<?php

namespace App\Livewire\LinkWizard;

use Throwable;
use Illuminate\View\View;
use Livewire\Attributes\Validate;
use Illuminate\Support\Facades\Http;
use Illuminate\Validation\ValidationException;
use Spatie\LivewireWizard\Components\StepComponent;

class FirstStep extends StepComponent
{
    #[Validate('required|url')]
    public string $url = '';

    public function stepInfo() : array
    {
        return [
            'label' => 'Link',
        ];
    }

    public function initialState() : array
    {
        return [
            'url' => $this->url,
        ];
    }

    public function render() : View
    {
        return view('livewire.link-wizard.first-step');
    }

    public function submit() : void
    {
        $this->validate();

        try {
            Http::head($this->url)->throw();

            $this->nextStep();
        } catch (Throwable $e) {
            throw ValidationException::withMessages([
                'url' => 'Your URL is invalid.',
            ]);
        }
    }
}
