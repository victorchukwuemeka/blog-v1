@props(['listing'])

<ul {{ $attributes }}>
    <li class="flex gap-2 items-center">
        <x-heroicon-o-building-office class="flex-none text-gray-500 size-4" />
        {{ $listing->company->name }}
    </li>

    @if ($listing->min_salary && $listing->max_salary)
        <li class="flex gap-2 items-center">
            <x-heroicon-o-banknotes class="flex-none text-gray-500 size-4" /> {{ Number::currency($listing->min_salary, $listing->currency ?? 'USD') }}—{{ Number::currency($listing->max_salary, $listing->currency ?? 'USD') }}
        </li>
    @endif

    @if ($listing->location)
        <li class="flex gap-2 items-center">
            <x-heroicon-o-map class="flex-none text-gray-500 size-4" />
            {{ $listing->location }}
        </li>
    @endif

    <li class="flex gap-2 items-center">
        <x-heroicon-o-home class="flex-none text-gray-500 size-4" />
        {{ ucfirst($listing->setting) }}
    </li>
</ul>
