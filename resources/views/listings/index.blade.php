<x-app
    title="The latest job offers for developers"
>
    <x-section
        :title="$listings->currentPage() > 1
            ? 'Page ' . $listings->currentPage()
            : 'Latest'"
        :big-title="$listings->currentPage() === 1"
    >
        @if ($listings->isNotEmpty())
            <div class="grid gap-12 lg:gap-x-16 lg:grid-cols-2">
                @foreach ($listings as $listing)
                    <article>
                        <h1 class="text-xl font-medium">
                            <a wire:navigate href="{{ route('listings.show', $listing) }}" target="_blank" class="text-blue-600 underline">
                                {{ $listing->title }}
                            </a>
                        </h1>

                        <x-listings.details :$listing class="mt-2" />

                        <div class="mt-4">
                            {!! Str::markdown($listing->description) !!}
                        </div>

                        <ul class="flex flex-wrap gap-y-2 gap-x-6 items-center mt-4">
                            @foreach ($listing->technologies as $technology)
                                <li class="flex gap-2 items-center font-normal">
                                    <x-heroicon-o-tag class="text-gray-500 size-4" />
                                    {{ $technology }}
                                </li>
                            @endforeach
                        </ul>

                        <p class="mt-4">
                            <span class="text-gray-400">Source:</span> {{ $listing->source }}
                        </p>
                    </article>
                @endforeach
            </div>
        @endif

        <x-pagination
            :paginator="$listings"
            class="mt-16"
        />
    </x-section>
</x-app>
