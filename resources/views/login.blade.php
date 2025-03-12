<x-app
    :hide-footer="true"
    :hide-navigation="true"
    title="Sign in"
>
    <div class="flex flex-col min-h-screen">
        <div class="container flex items-center justify-between mt-4">
            <x-logo />

            <a
                wire:navigate
                href="{{ route('home') }}"
                class="tracking-tight underline underline-offset-4 decoration-gray-600/30"
            >
                Home →
            </a>
        </div>

        <div class="grid flex-grow place-items-center">
            <div class="container text-center lg:max-w-screen-md">
                <div class="text-lg font-bold text-black sm:text-2xl md:text-3xl">
                    To continue, please sign in
                </div>

                <div class="mt-2 text-gray-600 text-balance sm:text-lg md:text-xl">
                    By signing in, you will be able to use the comments section and share links with my {{ Number::format(cache('visitors')) }} monthly visitors.
                </div>

                <x-btn href="{{ route('auth.redirect') }}" primary class="mt-8">
                    Sign in with GitHub
                </x-btn>
            </div>
        </div>
    </div>
</x-app>
