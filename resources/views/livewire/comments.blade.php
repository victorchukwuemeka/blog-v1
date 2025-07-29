<section
    id="comments"
    class="mt-24 scroll-mt-4"
>
    <div class="flex gap-8 justify-between items-center">
        <x-heading>
            {{ trans_choice(':count comment|:count comments', $commentsCount) }}
        </x-heading>

        <x-dropdown>
            <x-slot:btn
                class="flex gap-2 items-center font-medium text-blue-600"
            >
                @if ($sort === 'asc')
                    Oldest to newest
                @else
                    Newest to oldest
                @endif

                <x-heroicon-o-arrows-up-down
                    @class([
                        'transition-transform duration-300 size-4',
                        'rotate-180' => $sort === 'asc',
                    ])
                />
            </x-slot>

            <x-slot:items>
                <x-dropdown.item
                    wire:click="$set('sort', 'desc')"
                    @click.prevent="open = false"
                >
                    <x-heroicon-o-check @class([
                        'size-4 opacity-0',
                        'opacity-100' => $sort === 'desc',
                    ]) />

                    Newest to oldest
                </x-dropdown.item>

                <x-dropdown.item
                    wire:click="$set('sort', 'asc')"
                    @click.prevent="open = false"
                >
                    <x-heroicon-o-check @class([
                        'size-4 opacity-0',
                        'opacity-100' => $sort === 'asc',
                    ]) />

                    Oldest to newest
                </x-dropdown.item>
            </x-slot>
        </x-dropdown>
    </div>

    @if ($comments->isNotEmpty())
        <ul class="grid gap-8 mt-8">
            @foreach ($comments as $comment)
                <li>
                    <x-comment :$comment :parentId="$this->parentId" />
                </li>
            @endforeach
        </ul>

        <x-pagination :paginator="$comments" />
    @endif

    <div class="mt-16">
        <livewire:comment-form label="Your comment" />
    </div>
</section>
