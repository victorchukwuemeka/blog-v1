<div>
    <x-breadcrumbs class="container xl:max-w-screen-lg">
        <x-breadcrumbs.item href="{{ route('links.index') }}">
            Links
        </x-breadcrumbs.item>

        <x-breadcrumbs.item>
            Send a link
        </x-breadcrumbs.item>
    </x-breadcrumbs>

    <x-section :title="$this->stepInfo()['label']" class="container mt-16 md:max-w-screen-sm">
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

        @if ($title)
            <x-form wire:submit="submit" class="grid gap-4 mt-8">
                @if ($imageUrl)
                    <img src="{{ $imageUrl }}" class="object-cover shadow-md shadow-black/5 rounded-xl aspect-video ring-1 ring-black/5" />
                @endif

                <x-form.input
                    label="URL"
                    type="url"
                    id="url"
                    value="{{ $url }}"
                    required
                    disabled
                />

                <x-form.input
                    label="Title"
                    type="text"
                    id="title"
                    wire:model="title"
                    required
                />

                <x-form.textarea
                    label="Description"
                    rows="2"
                    id="description"
                    wire:model="description"
                />

                <x-btn primary class="mt-4 place-self-center">
                    Submit
                </x-btn>
            </x-form>
        @else
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 200 200" class="mx-auto mt-8 size-20"><circle fill="none" stroke-opacity="1" stroke="#92C5FD" stroke-width=".5" cx="100" cy="100" r="0"><animate attributeName="r" calcMode="spline" dur="2" values="1;80" keyTimes="0;1" keySplines="0 .2 .5 1" repeatCount="indefinite"></animate><animate attributeName="stroke-width" calcMode="spline" dur="2" values="0;25" keyTimes="0;1" keySplines="0 .2 .5 1" repeatCount="indefinite"></animate><animate attributeName="stroke-opacity" calcMode="spline" dur="2" values="1;0" keyTimes="0;1" keySplines="0 .2 .5 1" repeatCount="indefinite"></animate></circle></svg>
        @endif
    </x-section>
</div>
