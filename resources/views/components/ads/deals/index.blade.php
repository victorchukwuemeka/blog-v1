<a
    {{
        $attributes
            ->class('flex flex-col p-4 rounded-xl border transition-opacity hover:opacity-50 md:p-6 md:text-xl/tight')
            ->merge([
                'target' => '_blank',
            ])
    }}
>
    {{ $slot }}
</a>
