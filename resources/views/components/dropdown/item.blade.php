@if ($attributes->has('href'))
<a
@else
<button
@endif
    {{ $attributes->class([
        'flex items-center w-full gap-2 px-4 py-2 transition-colors hover:bg-blue-600/85 hover:text-white',
        'hover:bg-red-600/85' => $attributes->has('destructive'),
    ]) }}
>
    {{ $slot }}
@if ($attributes->has('href'))
</a>
@else
</button>
@endif
