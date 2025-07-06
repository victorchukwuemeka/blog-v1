<x-app
    :canonical="$post->canonical_url"
    :description="$post->description"
    :image="$post->image_url"
    :title="! empty($post->serp_title) ? $post->serp_title : $post->title"
>
    <div class="container 2xl:max-w-(--breakpoint-xl) grid lg:grid-cols-12 gap-16 lg:gap-8">
        <div class="lg:col-span-8 xl:col-span-9">
            <article>
                @if ($post->hasImage())
                    <img src="{{ $post->image_url }}" alt="{{ $post->title  }}" class="object-cover mb-12 w-full rounded-xl ring-1 shadow-xl ring-black/5 aspect-video" />
                @endif

                <h1 class="font-medium tracking-tight text-center text-black text-balance md:mt-16 text-3xl/none sm:text-4xl/none lg:text-5xl/none">
                    {{ $post->title }}
                </h1>

                <div class="mt-12 md:mt-16">
                    <div @class([
                        'grid grid-cols-2 gap-4 text-sm leading-tight md:grid-cols-4',
                        'xl:grid-cols-5' => $user?->isAdmin(),
                    ])>
                        <div class="flex-1 p-3 text-center bg-gray-50 rounded-lg">
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
                            <div class="flex-1 p-3 text-center bg-gray-50 rounded-lg transition-colors hover:bg-blue-50 group-hover:text-blue-900">
                                <img src="{{ $post->user->avatar }}" class="mx-auto mb-2 rounded-full size-6" />
                                Written by<br />
                                {{ $post->user->name }}
                            </div>
                        </a>

                        <a href="#comments" class="group">
                            <div @class([
                                'flex-1 p-3 text-center transition-colors rounded-lg bg-gray-50 hover:bg-blue-50 group-hover:text-blue-900',
                                'text-blue-600' => $post->comments_count > 0,
                            ])>
                                <x-heroicon-o-chat-bubble-oval-left-ellipsis class="mx-auto mb-2 opacity-75 size-6" />
                                {{ $post->comments_count }}<br />
                                {{ trans_choice('comment|comments', $post->comments_count) }}
                            </div>
                        </a>

                        <div class="flex-1 p-3 text-center bg-gray-50 rounded-lg">
                            <x-heroicon-o-clock class="mx-auto mb-2 opacity-75 size-6" />
                            {{ $post->read_time }} minutes<br />
                            read
                        </div>

                        @if ($user?->isAdmin())
                            <a href="{{ route('filament.admin.resources.posts.edit', $post) }}" class="group">
                                <div class="flex-1 p-3 text-center bg-gray-50 rounded-lg transition-colors hover:bg-blue-50 group-hover:text-blue-900">
                                    <x-heroicon-o-pencil-square class="mx-auto mb-2 opacity-75 size-6" />
                                    Edit<br />
                                    article
                                </div>
                            </a>
                        @endif
                    </div>

                    @if (! empty($headings = extract_headings_from_markdown($post->content)))
                        <x-table-of-contents
                            :$headings
                            class="mt-4 ml-0"
                        />
                    @endif

                    <x-prose class="mt-8">
                        {!! $post->formatted_content !!}

                        @if (! empty($post->recommendedPosts))
                            <hr />

                            <p>Did you like this article? Then, keep learning:</p>

                            <ul>
                                @foreach ($post->recommendedPosts as $post)
                                    <li>
                                        <a
                                            wire:navigate
                                            href="{{ route('posts.show', $post) }}"
                                            data-pirsch-event='Clicked on recommended post "{{ $post->title }}"'
                                        >
                                            {{ trim($post->reason, '.') }}
                                        </a>
                                    </li>
                                @endforeach
                            </ul>
                        @endif
                    </x-prose>
                </div>
            </article>

            @if ($post->comments_count)
                <div class="mt-24">
                    <livewire:comments :post-id="$post->id" />
                </div>
            @endif
        </div>

        <div class="lg:col-span-4 xl:col-span-3">
            @if (now()->isAfter('2025-08-03'))
                <x-ads.sidebar.sevalla />
            @else
                <x-ads.sidebar.vemetric />
            @endif
        </div>
    </div>

    {{-- This kind of information is only relevant for published posts. --}}
    @if ($post->published_at)
        <script type="application/ld+json">
            {
                "@context": "https://schema.org",
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
