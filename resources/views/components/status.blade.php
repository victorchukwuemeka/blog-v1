@if (session('status') || ! empty(request()->submitted))
    <div
        class="fixed bottom-4 left-1/2 text-center cursor-default shadow-lg shadow-blue-600/50 z-10 -translate-x-1/2 bg-blue-600/85 backdrop-blur-md text-white w-max min-w-[240px] px-4 py-3 font-medium rounded-lg"
        x-data="{ show: false }"
        x-cloak
        x-init="setTimeout(() => {
            show = true

            setTimeout(() => show = false, 5000)
        }, 100)"
        x-show="show"
        x-transition:enter-end="opacity-100 translate-y-0"
        x-transition:enter-start="opacity-0 translate-y-4"
        x-transition:enter="transition ease-out duration-300"
        x-transition:leave-end="opacity-0 translate-y-4"
        x-transition:leave="transition ease-in duration-300"
        @click="show = false"
    >
        @if (! empty(request()->submitted))
            Your link has been submitted for validation.
        @else
            {{ session('status') }}
        @endif
    </div>
@endsession
