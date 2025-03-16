<div id="comments">
    <h1 class="font-bold tracking-widest text-center text-black uppercase">
        {{ trans_choice(':count comment|:count comments', $comments->count()) }}
    </h1>

    <ul class="container grid gap-8 mt-8 md:max-w-screen-sm">
        <li>
            @foreach ($comments as $comment)
                <x-comment :$comment />
            @endforeach
        </li>
    </ul>
</div>