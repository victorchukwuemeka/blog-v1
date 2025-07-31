<div
    x-data="{ open: false }"
    @keydown.meta.k.window="open = true"
>
    <div
        class="fixed inset-0 z-20 backdrop-blur-md bg-black/75"
        x-cloak
        x-show="open"
        x-transition.opacity.duration.300ms
    ></div>

    <div
        class="grid overflow-y-scroll fixed inset-0 z-30 place-items-center p-4"
        @click="open = false"
    >
        <div
            class="bg-white p-2 pb-4 w-full max-w-[480px] rounded-xl shadow-2xl"
            x-cloak
            x-show="open"
            x-transition.duration.300ms
            x-trap.noscroll="open"
            @keydown.esc="open = false"
            @keydown.arrow-down.stop.prevent="$focus.next()"
            @keydown.arrow-up.stop.prevent="$focus.prev()"
            @click.stop
        >
            <div class="flex relative items-center">
                <x-heroicon-o-magnifying-glass
                    class="absolute left-3 top-1/2 text-gray-500 -translate-y-1/2 size-4"
                />

                <input
                    type="search"
                    wire:model.live="query"
                    placeholder="Search"
                    autofocus
                    class="flex-grow pr-3 pl-9 py-[.65rem] bg-transparent rounded-md border border-gray-200 placeholder-black/10"
                />
            </div>

            <div class="grid gap-8 -mx-2 mt-px">
                <div>
                    <p class="sticky -top-4 z-10 px-4 py-3 text-sm font-medium text-black uppercase border-b border-gray-200 backdrop-blur-md bg-white/75">Posts</p>

                    @if ($posts->isNotEmpty())
                        <ul>
                            @foreach ($posts as $post)
                                <li>
                                    <a
                                        wire:navigate
                                        href="{{ route('posts.show', $post) }}"
                                        class="block p-4 leading-tight transition-colors focus:outline-none focus:bg-blue-600/75 focus:text-white"
                                    >
                                        <p class="font-medium">{{ $post->title }}</p>
                                        <p class="mt-2 opacity-75">{{ $post->description }}</p>
                                    </a>
                                </li>
                            @endforeach
                        </ul>
                    @else
                        <p class="mt-4 text-center text-gray-500">
                            No posts found.
                        </p>
                    @endif
                </div>

                <div>
                    <p class="sticky -top-4 z-10 px-4 py-3 text-sm font-medium text-black uppercase border-gray-200 backdrop-blur-md border-y bg-white/75">Links</p>

                    @if ($links->isNotEmpty())
                        <ul class="mt-2">
                            @foreach ($links as $link)
                            <li>
                                <a
                                    wire:navigate
                                    href="{{ $link->url }}"
                                    class="block p-4 leading-tight transition-colors focus:outline-none focus:bg-blue-600/75 focus:text-white"
                                >
                                    <p class="font-medium">{{ $link->title }}</p>
                                    <p class="mt-2 opacity-75">{{ $link->description }}</p>
                                </a>
                            </li>
                            @endforeach
                        </ul>
                    @else
                        <p class="mt-4 text-center text-gray-500">No links found.</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
