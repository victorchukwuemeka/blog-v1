<x-app
    title="The latest job offers for developers"
>
    <x-section
        :title="$listings->currentPage() > 1
            ? 'Page ' . $listings->currentPage()
            : 'Latest'"
        :big-title="$listings->currentPage() === 1"
        class="lg:max-w-(--breakpoint-md)"
    >
        @if ($listings->isNotEmpty())
            <div class="grid gap-4">
                @foreach ($listings as $listing)
                    <a wire:navigate href="{{ route('listings.show', $listing) }}" target="_blank">
                        <article class="p-6 rounded-xl ring-1 shadow-md ring-black/10">
                            <div class="flex gap-16 justify-between items-start">
                                <p class="text-sm tracking-widest uppercase">
                                    {{ $listing->company->name }}
                                </p>

                                <p class="flex-none text-gray-500">
                                    {{ $listing->published_on->diffForHumans() }}
                                </p>
                            </div>

                            <h1 class="mt-2 font-medium tracking-tight max-w-2/3 text-xl/tight">
                                {{ $listing->title }}
                            </h1>

                            <p class="flex flex-wrap gap-2 items-center mt-4 leading-none">
                                @if (!empty($listing->locations))
                                    {!! collect($listing->locations)->join(' <span class="opacity-50 text-xs/none">/</span> ') !!}
                                @endif
                            </p>

                            <p class="flex flex-wrap gap-2 items-center mt-3 leading-none">
                                {{ $listing->setting }}

                                <span class="opacity-50 text-xs/none">/</span>

                                @if ($listing->min_salary && $listing->max_salary)
                                    {{ Number::currency($listing->min_salary, $listing->currency ?? 'USD') }}—{{ Number::currency($listing->max_salary, $listing->currency ?? 'USD') }}
                                @endif
                            </p>

                            <ul class="flex flex-wrap gap-y-1 gap-x-5 items-center mt-4">
                                @foreach ($listing->technologies as $technology)
                                    <li class="flex gap-2 items-center">
                                        <x-heroicon-o-tag class="text-gray-500 size-4" />
                                        {{ $technology }}
                                    </li>
                                @endforeach
                            </ul>

                            <p class="mt-4">
                                <span class="text-gray-400">Source:</span> {{ $listing->source }}
                            </p>
                        </article>
                    </a>
                @endforeach
            </div>
        @else
            <p class="text-center text-gray-500">
                There is no job offers at the moment.
            </p>
        @endif

        <x-pagination
            :paginator="$listings"
            class="mt-16"
        />
    </x-section>
</x-app>
