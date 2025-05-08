<x-app
    title="The best web development articles I wrote in {{ date('Y') }}"
    description="Level up as a web developer in {{ date('Y') }} with this collection of articles I wrote."
>
    <x-section
        title="The best web development articles I wrote in {{ date('Y') }}"
        :big-title="true"
        class="xl:max-w-(--breakpoint-lg) mt-0 md:mt-8"
    >
        @if ($categories->isNotEmpty())
            <div class="grid gap-10 mt-8 md:mt-16 gap-y-16 xl:gap-x-16 md:grid-cols-2">
                @foreach ($categories as $category)
                    <div>
                        <h2 class="font-bold tracking-widest text-black uppercase text-balance">
                            <a wire:navigate href="{{ route('categories.show', $category) }}">
                                About {{ $category->name }} <span class="ml-1">→</span>
                            </a>
                        </h2>

                        <ul class="grid gap-4 gap-6 mt-4 md:mt-6">
                            @foreach ($category->activity as $post)
                                <li>
                                    <div class="flex items-start gap-4 md:gap-6">
                                        <a wire:navigate href="{{ route('posts.show', $post) }}" class="flex-none mt-1">
                                            <img
                                                src="{{ $post->image_url }}"
                                                class="rounded shadow size-10 aspect-square ring-black/5 shadow-black/5 ring-1"
                                            />
                                        </a>

                                        <div class="leading-normal">
                                            <a wire:navigate href="{{ route('posts.show', $post) }}" class="block font-medium text-balance">
                                                {{ $post->title }}
                                            </a>

                                            <div class="flex flex-wrap items-center mt-2 text-gray-500 md:mt-1 gap-x-2">
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
