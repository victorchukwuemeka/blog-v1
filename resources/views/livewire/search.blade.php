<div
    class="overflow-x-scroll fixed inset-0 p-4"
    x-cloak
    x-data="{ open: false }"
    x-show="open"
    x-transition.duration.300ms
    x-trap.noscroll="open"
    @keydown.esc="open = false"
    @keydown.arrow-down.stop.prevent="$focus.next()"
    @keydown.arrow-up.stop.prevent="$focus.prev()"
    @keydown.meta.k.window="open = true"
    @search.window="open = true"
>
    <div
        class="bg-white overflow-hidden ring-1 ring-black/10 mx-auto w-full max-w-[480px] rounded-xl shadow-2xl"
        @click.away="open = false"
    >
        <div class="flex relative items-center">
            <x-heroicon-o-magnifying-glass
                class="absolute left-5 top-1/2 text-gray-500 -translate-y-1/2 size-4"
            />

            <input
                type="search"
                wire:model.live="query"
                placeholder="Search"
                autofocus
                class="flex-grow m-2 pr-3 pl-9 py-[.65rem] bg-transparent rounded-md border border-gray-200 placeholder-black/10"
            />
        </div>

        @if (! empty($query))
            <div>
                <div>
                    <p class="sticky -top-4 z-10 px-4 py-3 text-sm font-medium text-black uppercase border-b border-gray-200 backdrop-blur-md bg-white/75">Posts</p>

                    @if ($posts->isNotEmpty())
                        <ul>
                            @foreach ($posts as $post)
                                <li class="group">
                                    <a
                                        wire:navigate
                                        href="{{ route('posts.show', $post) }}"
                                        class="block p-4 leading-tight border-b transition-colors focus:outline-none focus:bg-blue-600/75 focus:text-white border-black/10 group-last:border-b-0"
                                    >
                                        <p class="font-medium">{{ $post->title }}</p>
                                        <p class="mt-2 opacity-75">{{ $post->description }}</p>
                                    </a>
                                </li>
                            @endforeach
                        </ul>
                    @else
                        <p class="p-4 text-center text-gray-500">
                            No posts found.
                        </p>
                    @endif
                </div>

                <div>
                    <p class="sticky top-0 z-10 px-4 py-3 text-sm font-medium text-black uppercase border-gray-200 backdrop-blur-md border-y bg-white/75">Links</p>

                    @if ($links->isNotEmpty())
                        <ul class="mt-2">
                            @foreach ($links as $link)
                                <li class="group">
                                    <a
                                        wire:navigate
                                        href="{{ $link->url }}"
                                        class="block p-4 leading-tight border-b transition-colors focus:outline-none focus:bg-blue-600/75 focus:text-white border-black/10 group-last:border-b-0"
                                    >
                                        <p class="font-medium">{{ $link->title }}</p>
                                        <p class="mt-2 opacity-75">{{ $link->description }}</p>
                                    </a>
                                </li>
                            @endforeach
                        </ul>
                    @else
                        <p class="p-4 text-center text-gray-500">No links found.</p>
                    @endif
                </div>
            </div>
        @endif
    </div>
</div>
