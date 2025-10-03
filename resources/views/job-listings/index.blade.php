<x-app
    title="The latest job offers for developers"
>
    <div class="text-center">
        <div class="font-medium tracking-tight text-black text-4xl/none md:text-5xl lg:text-7xl text-balance">
            Dozens of new job offers each week
        </div>

        <div class="mt-4 tracking-tight text-black/75 text-lg/tight sm:text-xl/tight md:text-2xl/tight">
            I gather job offers across the web and you apply. Deal?
        </div>

        <x-btn
            primary
            size="md"
            href="#listings"
            class="mt-7 md:mt-11"
        >
            Start applying
        </x-btn>
    </div>

    <div class="mt-24">
        <x-heading>Currently hiring</x-heading>

        <div class="flex justify-center items-center gap-16 mt-8">
            @foreach ($companies as $company)
                <img src="{{ $company->logo }}" class="max-w-[200px] max-h-16" {!! $company->extra_attributes !!} />
            @endforeach
        </div>
    </div>

    <x-section
        :title="$jobListings->currentPage() > 1
            ? 'Page ' . $jobListings->currentPage()
            : 'Latest job offers'"
        :big-title="$jobListings->currentPage() === 1"
        id="listings"
        class="lg:max-w-(--breakpoint-md) mt-24"
    >
        @if ($jobListings->isNotEmpty())
            <div class="grid gap-4">
                @foreach ($jobListings as $jobListing)
                    <a wire:navigate href="{{ route('job-listings.show', $jobListing) }}" target="_blank">
                        <article class="p-6 rounded-xl ring-1 shadow-md ring-black/10">
                            <div class="flex gap-16 justify-between items-start">
                                <p class="text-sm tracking-widest uppercase">
                                    {{ $jobListing->company->name }}
                                </p>

                                <p class="flex-none text-gray-500">
                                    {{ $jobListing->created_at->diffForHumans() }}
                                </p>
                            </div>

                            <h1 class="mt-2 font-medium tracking-tight max-w-2/3 text-xl/tight">
                                {{ $jobListing->title }}
                            </h1>

                            <p class="flex flex-wrap gap-2 items-center mt-4 leading-none">
                                @if (!empty($jobListing->locations))
                                    {!! collect($jobListing->locations)->join(' <span class="opacity-50 text-xs/none">/</span> ') !!}
                                @endif
                            </p>

                            <p class="flex flex-wrap gap-2 items-center mt-3 leading-none">
                                {{ ucfirst($jobListing->setting) }}

                                @if ($jobListing->min_salary && $jobListing->max_salary)
                                    <span class="opacity-50 text-xs/none">/</span>

                                    {{ Number::currency($jobListing->min_salary, $jobListing->currency ?? 'USD') }}—{{ Number::currency($jobListing->max_salary, $jobListing->currency ?? 'USD') }}
                                @endif
                            </p>

                            <ul class="flex flex-wrap gap-y-1 gap-x-5 items-center mt-4">
                                @foreach ($jobListing->technologies as $technology)
                                    <li class="flex gap-2 items-center">
                                        <x-heroicon-o-tag class="text-gray-500 size-4" />
                                        {{ $technology }}
                                    </li>
                                @endforeach
                            </ul>

                            <p class="mt-4">
                                <span class="text-gray-400">Source:</span> {{ $jobListing->source }}
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
            :paginator="$jobListings"
            class="mt-16"
        />
    </x-section>
</x-app>
