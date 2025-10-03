@props(['job'])

<div {{ $attributes->class('flex items-center gap-4 md:gap-6') }}>
    <div class="flex-none size-12 rounded-full ring-1 ring-black/10 grid place-items-center">
        <x-heroicon-o-building-office class="text-gray-500 size-6" />
    </div>

    <div>
        <a
            wire:navigate
            href="{{ route('job-listings.show', $job->slug) }}"
            class="font-bold transition-colors hover:text-blue-600"
            data-pirsch-event="Clicked job"
            data-pirsch-meta-title="{{ $job->title }}"
        >
            {{ $job->title }}
        </a>

        <div class="flex items-center gap-3 mt-1">
            <p>
                {{ $job->company->name }}
            </p>

            @if ($job->min_salary && $job->max_salary)
                <div class="text-xs opacity-50">/</div>

                <p class="flex items-center gap-2">
                    <x-heroicon-o-banknotes class="size-4 opacity-75" />
                    {{ Number::currency($job->min_salary, $job->currency ?? 'USD') }}—{{ Number::currency($job->max_salary, $job->currency ?? 'USD') }}
                </p>
            @endif
        </div>
    </div>
</div>
