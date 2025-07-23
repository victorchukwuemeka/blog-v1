@php
$description = Str::limit(
    strip_tags(Str::markdown($author->about)),
    160
);
@endphp

<x-app
    title="About {{ $author->name }}"
    :description="$description"
    :image="$author->avatar"
>
    <article class="container lg:max-w-(--breakpoint-md)">
        <header>
            <img
                loading="lazy"
                src="{{ $author->avatar }}"
                alt="{{ $author->name }}"
                class="mx-auto mt-1 rounded-full size-16"
            >

            <h1 class="mt-2 font-semibold text-center text-xl/tight">
                {{ $author->name }}
            </h1>

            @if ($author->company)
                <p class="text-center text-gray-400 text-lg/tight">
                    {{ $author->company }}
                </p>
            @endif
        </header>

        @if ($author->biography)
            <x-prose class="mt-6 md:mt-8">
                {!! Str::markdown($author->about) !!}
            </x-prose>
        @endif
    </article>

    <x-section title="Articles by {{ $author->name }}" class="mt-12 md:mt-16">
        @if ($posts->isNotEmpty())
            <x-posts-grid :$posts />
        @else
            <p class="-mt-4 text-center text-gray-500">
                So far, {{ $author->name }} didn't write any article.
            </p>
        @endif

        <x-pagination
            :paginator="$posts"
            class="mt-16"
        />
    </x-section>

    <x-section title="Links sent by {{ $author->name }}" class="mt-12 md:mt-16">
        @if ($links->isNotEmpty())
            <ul class="grid gap-10 gap-y-16 xl:gap-x-16 md:grid-cols-2 xl:grid-cols-3">
                @foreach ($links as $link)
                    <li>
                        <x-link :$link />
                    </li>
                @endforeach
            </ul>
        @else
            <p class="-mt-4 text-center text-gray-500">
                So far, {{ $author->name }} didn't send any link.
            </p>
        @endif

        <x-pagination
            :paginator="$links"
            class="mt-16"
        />
    </x-section>

    <script type="application/ld+json">
        {
            "@@context": "https://schema.org",
            "@type": "Person",
            "name": "{{ $author->name }}",
            "image": "{{ $author->avatar }}",
            "url": "{{ url()->current() }}",
            "description": "{{ $description }}",
            "sameAs": [
                "{{ $author->github_data['user']['html_url'] ?? '' }}"
            ]
        }
    </script>
</x-app>
