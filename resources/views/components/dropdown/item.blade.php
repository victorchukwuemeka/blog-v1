@if ($attributes->has('href'))
<a
@else
<button
@endif
    {{ $attributes->class([
        'flex items-center w-full gap-2 px-4 py-2 transition-colors focus:outline-none hover:text-white',
        'focus:bg-blue-900/5 focus:text-blue-900 hover:bg-blue-600/75' => ! $attributes->has('destructive'),
        'hover:bg-red-600/75 focus:bg-red-600/10 focus:text-red-900' => $attributes->has('destructive'),
    ]) }}
>
    {{ $slot }}
@if ($attributes->has('href'))
</a>
@else
</button>
@endif
