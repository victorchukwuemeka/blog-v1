@php
$description = Str::limit(
    strip_tags(Str::markdown($author->about)),
    160
);
@endphp

<x-app
    :description="$description"
    :image="$author->avatar"
    title="About {{ $author->name }}"
>
    <x-section class="mt-0 md:mt-8">
        <img
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

        @if ($author->biography)
            <x-prose class="mt-8 mb-10 lg:max-w-(--breakpoint-md) lg:mx-auto">
                {!! Str::markdown($author->about) !!}
            </x-prose>
        @endif
    </x-section>

    <x-section title="Articles by {{ $author->name }}" class="mt-8">
        <div class="mt-8">
            @if ($posts->isNotEmpty())
                <ul class="grid gap-10 gap-y-16 xl:gap-x-16 md:grid-cols-2 xl:grid-cols-3">
                    @foreach ($posts as $post)
                        <li>
                            <x-post :$post />
                        </li>
                    @endforeach
                </ul>
            @else
                <p class="-mt-4 text-center text-gray-500">
                    So far, {{ $author->name }} didn't write any article.
                </p>
            @endif
        </div>

        @if ($posts->hasPages())
            <div class="mt-16">
                {{ $posts->links() }}
            </div>
        @endif
    </x-section>

    <script type="application/ld+json">
        {
            "@context": "https://schema.org",
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