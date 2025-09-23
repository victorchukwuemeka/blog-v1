@props(['items'])

@if (! empty($items))
    <div {{ $attributes->class('px-4 py-6 mt-4 ml-0 rounded-lg bg-gray-50') }}>
        <x-heading class="text-sm">
            Table of contents
        </x-heading>

        <x-table-of-contents.items :$items class="mt-4 ml-0" />
    </div>
@endif
