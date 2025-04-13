<?php

namespace App\Livewire\LinkWizard;

use Embed\Embed;
use App\Models\Link;
use Illuminate\View\View;
use Livewire\Attributes\On;
use Livewire\Attributes\Url;
use Livewire\Attributes\Locked;
use Livewire\Attributes\Validate;
use Spatie\LivewireWizard\Components\StepComponent;

class SecondStep extends StepComponent
{
    #[Locked]
    #[Url(history: true)]
    public string $url;

    #[Locked]
    public ?string $imageUrl = null;

    #[Validate('required|string|min:3|max:255')]
    public string $title;

    #[Validate('nullable|string|max:255')]
    public ?string $description = null;

    public function stepInfo() : array
    {
        return [
            'label' => 'Details',
        ];
    }

    public function mount() : void
    {
        if (! $this->url) {
            $this->previousStep();
        }
    }

    public function render() : View
    {
        $this->dispatch('fetch')->self();

        return view('livewire.link-wizard.second-step');
    }

    #[On('fetch')]
    public function fetch() : void
    {
        $embed = (new Embed)->get($this->url);

        $this->imageUrl = $embed->image;
        $this->title = $embed->title;
        $this->description = $embed->description;
    }

    public function submit() : void
    {
        $this->validate();

        Link::query()->create([
            'user_id' => auth()->id(),
            'url' => $this->url,
            'image_url' => $this->imageUrl,
            'title' => $this->title,
            'description' => $this->description,
        ]);

        $this->redirect(route('links.index', ['submitted' => true]), true);
    }
}
