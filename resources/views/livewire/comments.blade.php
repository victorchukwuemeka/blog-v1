<div>
    <h1 class="font-bold tracking-widest text-center text-black uppercase">
        {{ trans_choice(':count comment|:count comments', $commentsCount) }}
    </h1>

    @if ($comments->isNotEmpty())
        <div class="grid gap-8 mt-8 mb-12 md:mb-16">
            @foreach ($comments as $comment)
                <x-comment :$comment />
            @endforeach
        </div>
    @endif
</div>