<section
    id="comments"
    class="mt-24 scroll-mt-4"
>
    <h1 class="font-bold tracking-widest text-center text-black uppercase">
        {{ trans_choice(':count comment|:count comments', $commentsCount) }}
    </h1>

    @if ($comments->isNotEmpty())
        <div class="grid gap-8 mt-8">
            @foreach ($comments as $comment)
                <x-comment :$comment />
            @endforeach
        </div>
    @endif

    <x-form class="flex gap-4 mt-16">
        @if (auth()->check())
            <img
                src="{{ $user->avatar }}"
                alt="{{ $user->name }}"
                class="flex-none rounded-full ring-1 shadow-sm shadow-black/5 ring-black/10 size-7 md:size-8"
            />
        @else
            <img
                src="https://www.gravatar.com/avatar/?d=mp"
                class="flex-none rounded-full ring-1 shadow-sm shadow-black/5 ring-black/10 size-7 md:size-8"
            />
        @endif

        <div class="grow">
            <textarea
                id="comment"
                name="comment"
                placeholder="Your comment"
                disabled
                class="px-3 py-2 w-full placeholder-gray-300 rounded-md border border-gray-200 shadow-sm resize-none shadow-black/5"
                x-autosize
            ></textarea>

            <x-btn primary disabled class="table mx-auto mt-4">
                Comment
            </x-btn>
        </div>
    </x-form>
</section>
