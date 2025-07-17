<x-app title="Your comments">
    <x-section
        :title="$comments->currentPage() > 1 ? 'Page ' . $comments->currentPage() : 'Your comments (' . $comments->total() .')'"
        class="md:max-w-screen-sm"
    >
        <ul class="grid gap-8">
            @foreach ($comments as $comment)
                <li>
                    <x-comment :$comment :hide-action-buttons="true" :hide-reply-button="true" />
                </li>
            @endforeach
        </ul>

        @if ($comments->hasPages())
            <div class="mt-16">
                {{ $comments->links() }}
            </div>
        @endif
    </x-section>
</x-app>
