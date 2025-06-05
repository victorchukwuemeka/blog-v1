@if ($attributes->has('href'))
<a
@else
<button
@endif
    {{
        $attributes
            ->class([
                'bg-gray-200 hover:bg-gray-100 inline-block font-medium rounded-xl tracking-tight transition-colors',
                'bg-blue-600! hover:bg-blue-500! text-white!' => $attributes->has('primary'),
                'bg-gray-100! text-gray-300!' => $attributes->has('disabled'),
                'px-[1.3rem] py-[.65rem]' => ! $attributes->has('size'),
                'px-6 py-3 text-lg' => 'md' === $attributes->get('size'),
                'px-[.65rem] py-[.35rem] text-sm rounded-md' => 'sm' === $attributes->get('size'),
                'px-[.65rem] py-[.35rem] text-xs rounded-sm' => 'xs' === $attributes->get('size'),
            ])
            ->merge([
                'data-pirsch-event' => 'Clicked “' . strip_tags($slot) . '”',
            ])
    }}
>
    {{ $slot }}
@if ($attributes->has('href'))
</a>
@else
</button>
@endif
