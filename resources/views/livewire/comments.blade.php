<section
    id="comments"
    class="mt-24 scroll-mt-4"
>
    <x-heading>
        {{ trans_choice(':count comment|:count comments', $commentsCount) }}
    </x-heading>

    @if ($comments->isNotEmpty())
        <ul class="grid gap-8 mt-8">
            @foreach ($comments as $comment)
                <li>
                    <x-comment :$comment :parentId="$this->parentId" />
                </li>
            @endforeach
        </ul>

        @if ($comments->hasPages())
            <div class="mt-8">
                {{ $comments->links() }}
            </div>
        @endif
    @endif

    <div class="mt-16">
        <livewire:comment-form />
    </div>
</section>
