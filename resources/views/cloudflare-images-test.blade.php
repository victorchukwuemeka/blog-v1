<x-app title="Cloudflare Images Test" :hideNavigation="true" :hideFooter="true">
    <div class="container mx-auto max-w-md">
        <h1 class="mb-6 text-2xl font-medium text-center">Cloudflare Images – Test Upload</h1>

        @if (session('success'))
            <div class="p-4 mb-4 text-green-800 bg-green-100 rounded">
                {{ session('success') }}
            </div>
        @endif

        @if ($errors->any())
            <div class="p-4 mb-4 text-red-800 bg-red-100 rounded">
                <ul class="list-disc list-inside">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('cloudflare-images.store') }}" enctype="multipart/form-data" class="space-y-4">
            @csrf
            <input type="file" name="image" accept="image/*" required class="block w-full rounded border-gray-300" />

            <button type="submit" class="px-4 py-2 text-white bg-blue-600 rounded">Upload</button>
        </form>

        @if (session('image_url'))
            <div class="mt-8 text-center">
                <p class="mb-2">Uploaded image URL:</p>
                <a href="{{ session('image_url') }}" target="_blank" class="text-blue-600 underline">{{ session('image_url') }}</a>

                <img src="{{ session('image_url') }}" alt="Uploaded image" class="mx-auto mt-4 rounded" />
            </div>
        @endif
    </div>
</x-app>
