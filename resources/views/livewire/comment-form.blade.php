<div>
    <x-form wire:submit="submit" class="flex gap-4">
        @if (auth()->guest())
            <a href="{{ route('auth.redirect') }}">
        @endif
            <img
                src="{{ auth()->check() ? $user->avatar : 'https://www.gravatar.com/avatar/?d=mp' }}"
                alt="{{ auth()->check() ? $user->name : 'Guest' }}"
                class="flex-none mt-1 rounded-full ring-1 shadow-sm shadow-black/5 ring-black/10 size-7 md:size-8"
            />
        @if (auth()->guest())
            </a>
        @endif

        <div class="grow">
            <label for="comment" class="text-sm font-bold uppercase">
                {{ $parentId ? 'Your reply' : 'Your comment' }}
            </label>

            <textarea
                id="comment"
                wire:model="commentContent"
                placeholder="{{ auth()->guest() ? 'Whoops, you need to log in first.' : 'Lorem ipsum dolor sit amet…' }}"
                @if (auth()->guest())
                    disabled
                @endif
                required
                wire:loading.attr="disabled"
                class="px-3 py-2 mt-1 w-full placeholder-gray-300 rounded-md border border-gray-200 shadow-sm resize-none shadow-black/5 disabled:text-gray-300"
                @if ($parentId)
                    x-init="$el.focus()"
                @endif
                x-autosize
            ></textarea>

            <div class="flex gap-2 justify-center items-center mt-4">
                @if (auth()->check())
                    <button
                        class="font-medium tracking-tight text-white bg-blue-600 rounded-xl transition-colors disabled:bg-gray-100 disabled:hover:bg-gray-100! disabled:text-gray-300! px-[1.3rem] py-[.65rem] hover:bg-blue-500"
                        wire:loading.attr="disabled"
                    >
                        {{ $parentId ? 'Reply' : 'Comment' }}
                    </button>

                    @if ($parentId)
                        <x-btn
                            type="button"
                            class="-order-1"
                            @click="$wire.$parent.$set('parentId', null)"
                        >
                            Cancel
                        </x-btn>
                    @endif
                @else
                    <x-btn
                        primary-alt
                        href="{{ route('auth.redirect') }}"
                    >
                        Log in
                    </x-btn>
                @endif
            </div>
        </div>
    </x-form>
</div>
