@props(['headings'])

<div {{ $attributes->class('px-4 py-6 mt-4 rounded-lg bg-gray-50') }}>
    <div class="text-sm font-bold tracking-widest text-center text-black uppercase">
        Table of contents
    </div>

    <x-table-of-contents.items :$headings class="mt-4 ml-0" />
</div>