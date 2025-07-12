<x-app
    title="Cloudflare Images"
    x-data="{
        dragging: false,
        file: null,
    }"
    @dragenter.prevent="dragging = true"
    @dragover.prevent="dragging = true"
    @dragleave.prevent="dragging = false"
    @drop.prevent="() => {
        dragging = false

        if ($event.dataTransfer.files.length) {
            $refs.file.files = $event.dataTransfer.files
            $refs.file.dispatchEvent(new Event('change', { bubbles: true }))
        }
    }"
>
    <x-section
        title="Cloudflare Images"
        class="md:max-w-(--breakpoint-sm)"
    >
        @if ($errors->any())
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        @endif

        <form
            method="POST"
            action="{{ route('upload-to-cloudflare-images') }}"
            enctype="multipart/form-data"
        >
            @csrf

            <input
                type="file"
                name="image"
                accept="image/*"
                required
                class="hidden"
                x-ref="file"
                @change="e => {
                    if (e.target.files[0]) {
                        $el.form.submit()
                    }
                }"
            />

            <div
                class="p-4 text-center bg-gray-50 rounded-xl transition-colors"
                x-bind:class="{ 'bg-blue-50 text-blue-900': dragging }"
                @click="$refs.file.click()"
            >
                <x-heroicon-o-photo class="mx-auto h-16" />

                <p x-show="!dragging">
                    Click or drag and drop to upload an image.
                </p>

                <p
                    x-cloak
                    x-show="dragging"
                >
                    Alright, drop it so the upload starts!
                </p>
            </div>
        </form>
    </x-section>

    @if (session('url'))
        <x-section
            title="Result"
            class="mt-16 md:mt-24 md:max-w-(--breakpoint-sm)"
        >
            <p>
                <a
                    href="{{ session('url') }}"
                    target="_blank"
                >
                    <img
                        src="{{ session('url') }}"
                        alt="Uploaded image"
                        class="mx-auto rounded-xl ring-1 shadow-lg ring-black/10"
                    />
                </a>
            </p>
        </x-section>
    @endif
</x-app>
