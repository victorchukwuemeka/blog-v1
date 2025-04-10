<div>
    <x-breadcrumbs class="container xl:max-w-screen-lg">
        <x-breadcrumbs.item href="{{ route('links.index') }}">
            Links
        </x-breadcrumbs.item>

        <x-breadcrumbs.item>
            Send a link
        </x-breadcrumbs.item>
    </x-breadcrumbs>

    <div class="container mt-16 md:max-w-screen-sm">
        <x-form wire:submit="submit" class="grid gap-4">
            <x-form.input
                label="URL"
                type="url"
                id="url"
                wire:model="url"
                required
            />

            <x-btn primary class="mt-4 place-self-center">
                Submit
            </x-btn>
        </x-form>
    </div>
</div>
