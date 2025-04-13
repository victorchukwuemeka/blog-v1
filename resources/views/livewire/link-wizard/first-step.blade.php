<div>
    <x-breadcrumbs class="container xl:max-w-screen-lg">
        <x-breadcrumbs.item href="{{ route('links.index') }}">
            Links
        </x-breadcrumbs.item>

        <x-breadcrumbs.item>
            Send a link
        </x-breadcrumbs.item>
    </x-breadcrumbs>

    <x-section class="mt-16 md:max-w-screen-sm">
        <x-slot:title>
            @foreach ($steps as $step)
                <span @class([
                    'text-blue-600' => $step->isCurrent(),
                    'text-gray-500' => ! $step->isCurrent(),
                ])>
                    {{ $step->label }}
                </span>

                @if (! $loop->last)
                    <span class="mx-2 text-gray-400">→</span>
                @endif
            @endforeach
        </x-slot>

        <x-form wire:submit="submit" class="grid gap-4 mt-8">
            <x-form.input
                label="URL"
                type="url"
                id="url"
                wire:model="url"
                required
                autofocus
            />

            <x-btn primary class="mt-4 place-self-center">
                Submit
            </x-btn>
        </x-form>
    </x-section>
</div>
