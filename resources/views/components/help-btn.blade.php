<div x-data="{ open: false }">
    <button @click="open = !open">
        <x-heroicon-o-question-mark-circle class="size-[1em] translate-y-[.125rem] opacity-75" />
        <span class="sr-only">What is this?</span>
    </button>

    <div
        class="bg-black/75 text-wrap backdrop-blur-md normal-case font-light tracking-normal py-3 px-4 text-white rounded"
        x-anchor="$el.parentElement"
        x-cloak
        x-show="open"
        x-transition
        @click.away="open = false"
    >
        {{ $slot }}
    </div>
</div>
