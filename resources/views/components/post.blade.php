@props(['post'])

<div {{ $attributes->class('flex flex-col h-full') }}>
    @if ($post->image_url)
        <a wire:navigate href="{{ route('posts.show', $post->slug) }}">
            <img src="{{ $post->image_url }}" alt="{{ $post->title  }}" class="object-cover transition-opacity shadow-md shadow-black/5 rounded-xl aspect-video hover:opacity-50 ring-1 ring-black/5" />
        </a>
    @endif

    @if (! empty($post->categories))
        <div class="flex gap-2 mt-6">
            @foreach ($post->categories as $category)
                <div class="px-2 py-1 text-xs font-medium uppercase border border-gray-200 rounded-sm">
                    {{ $category->name }}
                </div>
            @endforeach
        </div>
    @endif

    <div class="flex items-center justify-between gap-6 mt-5">
        <a wire:navigate href="{{ route('posts.show', $post->slug) }}" class="font-bold transition-colors text-xl/tight hover:text-blue-600">
            {{ $post->title }}
        </a>

        <img
            src="{{ $post->user->gravatar_url }}"
            alt="{{ $post->user->name }}"
            class="rounded-full ring-1 ring-black/5 size-10"
        />
    </div>

    <div class="mt-4 grow">
        {!! Str::markdown($post->description) !!}
    </div>

    <div class="grid grid-cols-3 gap-4 mt-6 text-sm/tight">
        <div class="flex-1 p-3 text-center rounded-lg bg-gray-50">
            <x-heroicon-o-calendar class="mx-auto mb-1 opacity-75 size-5" />
            {{ ($post->modified_at ?? $post->published_at)->isoFormat('ll') }}
        </div>

        <a href="{{ route('posts.show', $post->slug) }}#comments" class="group">
            <div class="flex-1 p-3 text-center transition-colors rounded-lg bg-gray-50 hover:bg-blue-50 group-hover:text-blue-900">
                <x-heroicon-o-chat-bubble-oval-left-ellipsis class="mx-auto mb-1 opacity-75 size-5" />
                {{ $post->comments_count }} {{ trans_choice('comment|comments', $post->comments_count) }}
            </div>
        </a>

        <div class="flex-1 p-3 text-center rounded-lg bg-gray-50">
            <x-heroicon-o-clock class="mx-auto mb-1 opacity-75 size-5" />
            {{ $readTime ?? 0 }} minutes
        </div>
    </div>
</div>
