<a
    {{
        $attributes
            ->class('grid sticky top-4 gap-4 p-4 !pt-6 leading-tight bg-orange-50/75 rounded-xl text-orange-900')
            ->merge([
                'href' => 'https://vemetric.com?utm_source=benjamin_crozat&utm_medium=sidebar',
                'target' => '_blank',
            ])
    }}
>
    <img src="https://www.gravatar.com/avatar/d58b99650fe5d74abeb9d9dad5da55ad?s=256" alt="Benjamin Crozat" class="mx-auto h-12 rounded-full" />

    <p class="text-center text-lg/tight"><strong class="font-semibold text-orange-900">“Successful developers automate”</strong></p>

    <p>Don't waste time on server maintenance. Let Sevalla do it for you.</p>

    <ul class="grid gap-1 -mt-2 ml-3 list-disc list-inside">
        <li>PHP and Laravel-friendly</li>
        <li>Multiple environments</li>
        <li>Scheduled tasks and jobs</li>
        <li>Built-in security</li>
        <li><strong class="font-medium text-orange-950">Get $50 free credits</strong></li>
    </ul>

    <img src="{{ Vite::asset('resources/img/screenshots/sevalla.webp') }}" alt="Sevalla's interface" class="mt-1 rounded" />

    <x-btn primary class="w-full bg-orange-500! hover:bg-orange-400! mt-2 text-center !rounded-md cursor-pointer">
        Claim $50 free credits
    </x-btn>
</a>
