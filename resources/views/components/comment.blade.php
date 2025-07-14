@props(['comment'])

<div {{ $attributes }}>
    <div class="flex gap-4">
        <img
            src="{{ $comment->user->avatar }}"
            alt="{{ $comment->user->name }}"
            class="flex-none mt-1 rounded-full ring-1 shadow-sm shadow-black/5 ring-black/10 size-7 md:size-8"
        />

        <div class="grow">
            <div class="flex gap-4 justify-between">
                <div>
                    <a href="{{ $comment->user->github_data['user']['html_url'] }}" target="_blank" class="font-medium">
                        {{ $comment->user->name }}
                    </a>

                    <span class="ml-1 text-gray-500">
                        {{ $comment->created_at->diffForHumans(short: true) }}
                    </span>
                </div>

                <div>
                    @can('delete', $comment)
                        <button
                            class="font-medium text-red-600"
                            wire:click="delete({{ $comment->id }})"
                            wire:confirm="Are you sure you want to delete this comment?"
                            wire:loading.attr="disabled"
                        >
                            <x-heroicon-o-trash class="size-4" />
                            <span class="sr-only">Delete</span>
                        </button>
                    @endcan
                </div>
            </div>

            <div class="px-4 py-3 mt-2 bg-gray-100 rounded-lg">
                <x-prose>
                    {!! Str::markdown($comment->content) !!}
                </x-prose>

                <div class="mt-2 text-right">
                    <button
                        class="inline-flex disabled:opacity-30 gap-[.35rem] items-center font-medium"
                        wire:click="$set('parentId', {{ $comment->id }})"
                        {{ $this->parentId === $comment->id ? 'disabled' : '' }}
                    >
                        Reply
                        <x-heroicon-o-arrow-uturn-down class="size-4 -scale-x-100" />
                    </button>
                </div>
            </div>
        </div>
    </div>

    @if ($this->parentId === $comment->id)
        <div class="mt-8 ml-11 md:ml-12">
            <livewire:comment-form wire:key="comment-form-{{ $comment->id }}" :parentId="$comment->id" />
        </div>
    @endif

    @if ($comment->children->isNotEmpty())
        <div class="grid gap-8 mt-8 ml-11 md:ml-12">
            @foreach ($comment->children as $child)
                <x-comment :comment="$child" />
            @endforeach
        </div>
    @endif
</div>
