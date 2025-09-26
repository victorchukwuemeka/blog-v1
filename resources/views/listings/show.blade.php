<x-app
    title="$listing->title"
    description="$listing->description"
>
    <div class="container 2xl:max-w-(--breakpoint-xl) grid lg:grid-cols-12 gap-16 lg:gap-12">
        <div class="lg:col-span-8 xl:col-span-9">
            <article>
                <h1 class="font-medium tracking-tight text-black text-balance text-3xl/none sm:text-4xl/none lg:text-5xl/none">
                    {{ $listing->title }}
                </h1>

                <x-listings.details :$listing class="grid mt-8 lg:hidden sm:grid-cols-2" />

                <x-prose class="mt-8">
                    <h2>About the job</h2>

                    {!! Str::markdown($listing->content) !!}

                    <h2>About {{ $listing->company->name }}</h2>

                    {!! Str::markdown($listing->company->about) !!}
                </x-prose>
            </article>
        </div>

        <div class="hidden lg:block lg:col-span-4 xl:col-span-3">
            <x-heading class="text-left">Details</x-heading>

            <x-listings.details :$listing class="mt-4" />

            <x-heading class="mt-8 text-left">How to apply</x-heading>

            <ul class="grid gap-2 mt-4 ml-3 list-disc list-inside">
                @foreach ($listing->how_to_apply as $step)
                    <li>{{ $step }}</li>
                @endforeach
            </ul>

            <x-btn primary class="mt-6 w-full">
                Apply now
            </x-btn>
        </div>
    </div>
</x-app>
