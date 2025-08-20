<x-app title="The latest articles about web development in {{ date('Y') }}">
    <x-section
        :title="$posts->currentPage() > 1
            ? 'Page ' . $posts->currentPage()
            : 'Latest'"
        :big-title="$posts->currentPage() === 1"
    >
        @if ($posts->isNotEmpty())
            <x-posts-grid :$posts>
                @if ($posts->currentPage() === 1)
                    <li>
                        <a href="{{ route('advertise') }}#products">
                            <div class="grid place-items-center px-4 py-8 h-full leading-tight text-center rounded-xl transition-colors md:text-xl/tight bg-blue-50/50 hover:bg-blue-50/25">
                                <div>
                                    <p>Your business here for a week.</p>
                                    <p>Forever on my blog.</p>
                                    <x-btn primary class="mt-4 text-base! pointer-events-none md:mt-8">Advertise</x-btn>
                                </div>
                            </div>
                        </a>
                    </li>
                @endif
            </x-posts-grid>
        @endif

        <x-pagination
            :paginator="$posts"
            class="mt-16"
        />
    </x-section>
</x-app>
