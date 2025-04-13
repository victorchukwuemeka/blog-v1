<div>
    <x-link-wizard.breadcrumbs class="container xl:max-w-screen-lg" />

    <x-section class="mt-16 md:max-w-screen-sm">
        <x-link-wizard.steps :$steps />

        <x-form wire:submit="submit" class="grid gap-4 mt-8">
            <x-form.input
                label="URL"
                type="url"
                id="url"
                wire:model="url"
                required
                autofocus
            />

            <x-btn primary class="mt-4 !text-blue-900 hover:!bg-blue-100 !bg-blue-50 place-self-center">
                Next
            </x-btn>
        </x-form>
    </x-section>
</div>
