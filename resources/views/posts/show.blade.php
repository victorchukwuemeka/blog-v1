<x-app
    :canonical="$post->canonical_url"
    :description="$post->description"
    :hide-ad="$post->is_commercial"
    :image="$post->image_url"
    :title="! empty($post->serp_title) ? $post->serp_title : $post->title"
>
    <div @class([
        'container lg:max-w-(--breakpoint-md)',
        '2xl:max-w-(--breakpoint-xl) grid lg:grid-cols-12 gap-16 lg:gap-12' => ! $post->is_commercial,
    ])>
        <div @class([
            'lg:col-span-8 xl:col-span-9' => ! $post->is_commercial,
        ])>
            <article>
                @if ($post->hasImage())
                    <img
                        src="{{ $post->image_url }}"
                        alt="{{ $post->title  }}"
                        class="object-cover mb-12 w-full rounded-xl ring-1 shadow-xl md:mb-16 ring-black/5 aspect-video"
                    />
                @endif

                <p class="text-sm font-normal tracking-widest text-center uppercase md:text-base">
                    {{ trans_choice(':count minute|:count minutes', $post->read_time) }}
                    read
                </p>

                <h1 class="mt-2 font-medium tracking-tight text-center text-black text-balance text-3xl/none sm:text-4xl/none lg:text-5xl/none">
                    {{ $post->title }}
                </h1>

                <div class="grid grid-cols-2 gap-4 mt-12 text-sm leading-tight md:mt-16 md:grid-flow-col md:grid-cols-none md:auto-cols-fr">
                    <div class="p-3 text-center bg-gray-50 rounded-lg">
                        <x-heroicon-o-calendar class="mx-auto mb-2 opacity-75 size-6" />

                        @if ($post->modified_at)
                            Modified
                        @elseif ($post->published_at)
                            Published
                        @else
                            Drafted
                        @endif

                        <br />

                        {{ ($post->modified_at ?? $post->published_at ?? $post->created_at)->isoFormat('ll') }}
                    </div>

                    <a
                        wire:navigate
                        href="{{ route('authors.show', $post->user) }}"
                    >
                        <div class="p-3 text-center bg-gray-50 rounded-lg transition-colors hover:bg-blue-50 hover:text-blue-900">
                            <img src="{{ $post->user->avatar }}" class="mx-auto mb-2 rounded-full size-6" />
                            Written by<br />
                            {{ $post->user->name }}
                        </div>
                    </a>

                    @if (! $post->is_commercial)
                        <a href="#comments">
                            <div @class([
                                'flex-1 p-3 text-center bg-gray-50 rounded-lg transition-colors hover:bg-blue-50 hover:text-blue-900',
                                'text-blue-600 bg-blue-50!' => $post->comments_count,
                            ])>
                                <x-heroicon-o-chat-bubble-oval-left-ellipsis class="mx-auto mb-2 opacity-75 size-6" />
                                {{ $post->comments_count }}<br />
                                {{ trans_choice('comment|comments', $post->comments_count) }}
                            </div>
                        </a>
                    @endif

                    <x-dropdown>
                        <x-slot:btn
                            data-pirsch-event='Clicked "Actions"'
                            class="p-3 w-full h-full text-center bg-gray-50 rounded-lg transition-colors hover:bg-blue-50 hover:text-blue-900"
                        >
                            <x-heroicon-o-ellipsis-horizontal
                                class="mx-auto transition-transform size-6 md:size-7"
                                x-bind:class="{ 'rotate-90': open }"
                            />
                            Actions
                        </x-slot>

                        <x-slot:items>
                            @if ($user?->isAdmin())
                                <x-dropdown.divider>
                                    Admin
                                </x-dropdown.divider>

                                <x-dropdown.item
                                    href="{{ route('filament.admin.resources.posts.edit', $post) }}"
                                    data-pirsch-event='Clicked "Edit article"'
                                >
                                    <x-heroicon-o-pencil-square class="size-4" />
                                    Edit article
                                </x-dropdown.item>
                            @endif

                            <x-dropdown.divider>
                                Chat
                            </x-dropdown.divider>

                            <x-dropdown.item
                                :href="'https://chatgpt.com/?q=' . urlencode($post->toPrompt())"
                                target="_blank"
                                data-pirsch-event='Clicked "Ask ChatGPT"'
                            >
                                <x-icon-openai class="size-4" />
                                Ask ChatGPT
                            </x-dropdown.item>

                            <x-dropdown.item
                                :href="'https://claude.ai/new?q=' . urlencode($post->toPrompt())"
                                target="_blank"
                                data-pirsch-event='Clicked "Ask Claude"'
                            >
                                <x-icon-claude class="size-4" />
                                Ask Claude
                            </x-dropdown.item>
                        </x-slot>
                    </x-dropdown>
                </div>

                @if (! empty($headings = extract_headings_from_markdown($post->content)))
                    <x-table-of-contents
                        :$headings
                        class="mt-4 ml-0"
                    />
                @endif

                <x-prose class="mt-8">
                    {!! $post->formatted_content !!}

                    @if ($post->link)
                        <p>
                            <a href="{{ $post->link->url }}" target="_blank">
                                Read more on {{ $post->link->domain }} →
                            </a>
                        </p>
                    @endif

                    @if (! empty($post->recommendedPosts) && ! $post->is_commercial)
                        <hr />

                        <p>Did you like this article? Then, keep learning:</p>

                        <ul>
                            @foreach ($post->recommendedPosts as $recommendedPost)
                                <li>
                                    <a
                                        wire:navigate
                                        href="{{ route('posts.show', $recommendedPost) }}"
                                        data-pirsch-event='Clicked on recommended post "{{ $recommendedPost->title }}"'
                                    >
                                        {{ trim($recommendedPost->reason, '.') }}
                                    </a>
                                </li>
                            @endforeach
                        </ul>
                    @endif
                </x-prose>
            </article>

            @if (! $post->is_commercial)
                <div class="mt-24">
                    <livewire:comments :post-id="$post->id" />
                </div>
            @endif
        </div>

        @if (! $post->is_commercial)
            <div class="lg:col-span-4 xl:col-span-3">
                @if (now()->isAfter('2025-08-03'))
                    <x-ads.sidebar.sevalla
                        class="opacity-0 duration-[600ms] delay-300 md:delay-600 transition-[opacity,translate] translate-y-4"
                        x-bind:class="{ 'opacity-100 !translate-y-0': show }"
                        x-data="{ show: false }"
                        x-intersect:enter="show = true"
                    />
                @else
                    <x-ads.sidebar.vemetric
                        class="opacity-0 duration-[600ms] delay-300 md:delay-600 transition-[opacity,translate] translate-y-4"
                        x-bind:class="{ 'opacity-100 !translate-y-0': show }"
                        x-data="{ show: false }"
                        x-intersect:enter="show = true"
                    />
                @endif

                @if ($comment = $post->comments()->latest()->first())
                    <div class="hidden mt-16 md:block">
                        <p class="font-bold tracking-widest text-black uppercase text-balance">
                            Latest comment
                        </p>

                        <div class="flex gap-4 mt-6">
                            <img
                                src="{{ $comment->user->avatar }}"
                                alt="{{ $comment->user->name }}"
                                class="flex-none mt-1 rounded-full ring-1 shadow-sm shadow-black/5 ring-black/10 size-7 md:size-8"
                            />

                            <div>
                                <p>
                                    <a href="{{ $comment->user->github_data['user']['html_url'] }}" target="_blank" class="font-medium">
                                        {{ $comment->user->name }}
                                    </a>

                                    <span class="ml-1 text-gray-500">
                                        {{ $comment->created_at->diffForHumans(short: true) }}
                                    </span>
                                </p>

                                <x-prose class="mt-2 leading-normal text-gray-500">
                                    {{ $comment->truncated }}
                                </x-prose>

                                <p class="mt-2">
                                    <a href="#comments" class="font-medium underline">
                                        Check comments →
                                    </a>
                                </p>
                            </div>
                        </div>
                    </div>
                @endif
            </div>
        @endif
    </div>

    {{-- This kind of information is only relevant for published posts. --}}
    @if ($post->published_at)
        <script type="application/ld+json">
            {
                "@@context": "https://schema.org",
                "@type": "Article",
                "author": {
                    "@type": "Person",
                    "name": "{{ $post->user->name }}",
                    "url": "{{ route('home') }}#about"
                },
                "headline": "{{ $post->title }}",
                "description": "{{ $post->description }}",
                "image": "{{ $post->image_url }}",
                "datePublished": "{{ $post->published_at->toIso8601String() }}",
                "dateModified": "{{ $post->modified_at?->toIso8601String() ?? $post->published_at->toIso8601String() }}"
            }
        </script>
    @endif
</x-app>
