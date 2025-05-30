<div>
    <h1 class="font-bold tracking-widest text-center text-black uppercase">
        {{ trans_choice(':count comment|:count comments', $commentsCount) }}
    </h1>

    <div class="grid gap-8 mt-8">
        @foreach ($comments as $comment)
            <x-comment :$comment />
        @endforeach
    </div>

    @auth
    @else
        <div class="text-center">
            <div class="text-gray-500">
                You need to be signed in to comment this post.
            </div>

            <x-btn href="{{ route('auth.redirect') }}" primary class="mt-4">
                Sign in with GitHub
            </x-btn>
        </div>
    @endauth
</div>