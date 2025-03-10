@props(['post'])

<div {{ $attributes }}>
    @if ($post['image'])
        <a wire:navigate href="{{ route('posts.show', $post['slug']) }}">
            <img src="{{ $post['image'] }}" alt="{{ $post['title']  }}" class="object-cover transition-opacity shadow-md shadow-black/5 rounded-xl aspect-video hover:opacity-50 ring-1 ring-black/5" />
        </a>
    @endif

    <div class="flex items-center justify-between gap-6 mt-6">
        <a wire:navigate href="{{ route('posts.show', $post['slug']) }}" class="font-bold transition-colors text-xl/tight hover:text-blue-600">
            {{ $post['title'] }}
        </a>

        <img
            src="https://www.gravatar.com/avatar/d58b99650fe5d74abeb9d9dad5da55ad?s=256"
            alt="Benjamin Crozat"
            class="rounded-full ring-1 ring-black/5 size-10"
        />
    </div>

    <div class="mt-4">
        {!! Str::markdown($post['description']) !!}
    </div>

    <div class="grid grid-cols-3 gap-4 mt-6 text-sm/tight">
        <div class="flex-1 p-3 text-center rounded-lg bg-gray-50">
            <x-heroicon-o-calendar class="mx-auto mb-1 opacity-75 size-5" />
            {{ ($post['modified_at'] ?? $post['published_at'])->isoFormat('ll') }}
        </div>

        <div class="flex-1 p-3 text-center rounded-lg bg-gray-50">
            <x-heroicon-o-chat-bubble-oval-left-ellipsis class="mx-auto mb-1 opacity-75 size-5" />
            {{ $post['comments_count'] }} {{ trans_choice('comment|comments', $post['comments_count']) }}
        </div>

        <div class="flex-1 p-3 text-center rounded-lg bg-gray-50">
            <x-heroicon-o-clock class="mx-auto mb-1 opacity-75 size-5" />
            {{ $readTime ?? 0 }} minutes
        </div>
    </div>
</div>
