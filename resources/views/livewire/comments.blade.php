<section
    id="comments"
    class="mt-24 scroll-mt-4"
>
    <h1 class="font-bold tracking-widest text-center text-black uppercase">
        {{ trans_choice(':count comment|:count comments', $commentsCount) }}
    </h1>

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
