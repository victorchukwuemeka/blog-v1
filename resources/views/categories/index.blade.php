<x-app
    title="Categories"
    description="Level up as a web developer in {{ date('Y') }} with this collection of articles I wrote sorted by category."
>
    <x-section
        title="Categories"
        :big-title="true"
        class="xl:max-w-(--breakpoint-lg)"
    >
        @if ($categories->isNotEmpty())
            <div class="grid gap-10 gap-y-16 xl:gap-x-16 md:grid-cols-2">
                @foreach ($categories as $category)
                    <div>
                        <h2 class="font-bold tracking-widest text-black uppercase text-balance">
                            <a wire:navigate href="{{ route('categories.show', $category) }}" class="underline decoration-1 underline-offset-8 decoration-black/30">
                                {{ $category->name }} →
                            </a>
                        </h2>

                        <ul class="grid gap-8 mt-6 md:gap-6">
                            @foreach ($category->activity as $post)
                                <li>
                                    <div class="flex gap-4 items-start md:gap-6">
                                        <a wire:navigate href="{{ route('posts.show', $post) }}" class="flex-none mt-1">
                                            <img
                                                loading="lazy"
                                                src="{{ $post->image_url }}"
                                                class="rounded ring-1 shadow size-10 aspect-square ring-black/5 shadow-black/5"
                                            />
                                        </a>

                                        <div class="leading-normal">
                                            <a wire:navigate href="{{ route('posts.show', $post) }}" class="font-medium text-balance">
                                                {{ $post->title }}
                                            </a>

                                            <div class="flex flex-wrap gap-x-2 items-center mt-2 text-gray-500 md:mt-1">
                                                <div class="flex items-center">
                                                    {{ $post->published_at->isoFormat('LL') }}

                                                    <div class="ml-2 text-xs opacity-50">/</div>
                                                </div>

                                                <div class="flex items-center">
                                                    {{ $post->user->name }}

                                                    <div class="ml-2 text-xs opacity-50">/</div>
                                                </div>

                                                <div class="flex items-center">
                                                    <a href="{{ route('posts.show', $post) }}#comments" class="underline decoration-gray-600/30 underline-offset-4 decoration-1">
                                                        {{ trans_choice(':count comment|:count comments', $post->comments_count) }}
                                                    </a>

                                                    <div class="ml-2 text-xs opacity-50">/</div>
                                                </div>

                                                <div class="flex items-center">
                                                    {{ trans_choice(':count minute|:count minutes', $post->read_time) }} read
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                @endforeach
            </div>
        @endif
    </x-section>
</x-app>
