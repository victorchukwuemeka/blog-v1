<x-app title="Categories">
    <x-breadcrumbs class="container xl:max-w-(--breakpoint-lg)">
        <x-breadcrumbs.item>
            Categories
        </x-breadcrumbs.item>
    </x-breadcrumbs>

    <x-section class="mt-12 xl:max-w-(--breakpoint-lg)">
        @if ($categories->isNotEmpty())
            <div class="grid gap-10 mt-8 gap-y-16 xl:gap-x-16 md:grid-cols-2">
                @foreach ($categories as $category)
                    <div>
                        <h2 class="font-bold tracking-widest text-black uppercase text-balance">
                            <a wire:navigate href="{{ route('categories.show', $category) }}">
                                {{ $category->name }} <span class="ml-1">→</span>
                            </a>
                        </h2>

                        <ul class="grid gap-1 mt-4 ml-3 list-disc list-inside">
                            @foreach ($category->activity as $post)
                                <li>
                                    <a wire:navigate href="{{ route('posts.show', $post) }}" class="underline decoration-1 decoration-gray-600/30 underline-offset-4 line-clamp-1">
                                        {{ $post->title }}
                                    </a>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                @endforeach
            </div>
        @endif
    </x-section>
</x-app>
