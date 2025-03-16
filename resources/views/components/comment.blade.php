@props(['comment'])

<div {{ $attributes }}>
    <div class="flex gap-4">
        <img
            src="{{ $comment->user->avatar }}"
            alt="{{ $comment->user->name }}"
            class="flex-none mt-1 rounded-full shadow-md ring-1 ring-black/10 size-7 md:size-8"
        />

        <div class="flex-grow">
            <div>
                <a href="{{ $comment->user->github_data['user']['html_url'] }}" target="_blank" class="font-medium">
                    {{ $comment->user->name }}
                </a>

                <span class="ml-1 text-gray-500">
                    {{ $comment->created_at->diffForHumans() }}
                </span>
            </div>

            <x-prose class="px-4 py-3 mt-2 bg-gray-100 rounded-lg">
                {!! Str::markdown($comment->content) !!}
            </x-prose>
        </div>
    </div>

    @if ($comment->children->isNotEmpty())
        <div class="grid gap-8 mt-8 ml-11 md:ml-12">
            @foreach ($comment->children as $child)
                <x-comment :comment="$child" />
            @endforeach
        </div>
    @endif
</div>