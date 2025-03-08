<div {{ $attributes->class('h-px my-2 bg-black/10') }}></div>

@if ($slot->hasActualContent())
    <div class="px-4 py-2 text-xs font-bold tracking-widest uppercase">
        {{ $slot }}
    </div>
@endif
