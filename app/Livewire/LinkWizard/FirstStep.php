<?php

namespace App\Livewire\LinkWizard;

use Throwable;
use Illuminate\View\View;
use Livewire\Attributes\Url;
use Livewire\Attributes\Validate;
use Illuminate\Support\Facades\Http;
use Illuminate\Validation\ValidationException;
use Spatie\LivewireWizard\Components\StepComponent;

class FirstStep extends StepComponent
{
    #[Validate('required|url')]
    #[Url(history: true, keep: true)]
    public string $url = '';

    public function stepInfo() : array
    {
        return [
            'label' => 'Your link',
        ];
    }

    public function mount() : void
    {
        if ($this->url) {
            $this->prepareForNextStep();
        }
    }

    public function render() : View
    {
        return view('livewire.link-wizard.first-step');
    }

    public function submit() : void
    {
        try {
            $this->prepareForNextStep();
        } catch (Throwable $e) {
            throw ValidationException::withMessages([
                'url' => 'Your URL is invalid.',
            ]);
        }
    }

    protected function prepareForNextStep() : void
    {
        $this->validate();

        Http::head($this->url)->throw();

        $this->nextStep();
    }
}
