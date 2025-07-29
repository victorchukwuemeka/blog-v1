<x-app>
    <x-section title="Your comments" class="lg:max-w-screen-md">
        <div class="grid gap-8 mt-8 md:gap-12">
            @foreach ($comments as $comment)
                <div class="flex gap-4">
                    <img
                        loading="lazy"
                        src="{{ $comment->user->avatar }}"
                        alt="{{ $comment->user->name }}"
                        class="flex-none mt-1 rounded-full ring-1 shadow-sm shadow-black/5 ring-black/10 size-7 md:size-8"
                    />

                    <div>
                        <div>
                            <a href="{{ $comment->user->github_data['user']['html_url'] }}" target="_blank" class="font-medium">
                                {{ $comment->user->name }}
                            </a>

                            <span class="ml-1 text-gray-500">
                                {{ $comment->created_at->diffForHumans(short: true) }}
                            </span>
                        </div>

                        <x-prose>
                            {!! Str::lightdown($comment->content) !!}

                            <p>
                                <a wire:navigate href="{{ route('posts.show', $comment->post) }}" class="font-medium underline">Check out the post →</a>
                            </p>
                        </x-prose>
                    </div>
                </div>
            @endforeach
        </div>

        <x-pagination
            :paginator="$comments"
            class="mt-16"
        />
    </x-section>
</x-app>
