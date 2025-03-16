@props(['headings'])

<ul {{ $attributes->class('ml-4 grid gap-1') }}>
    @foreach ($headings as $heading)
        <li>
            <a href="#{{ $heading['slug'] }}" class="font-medium group line-clamp-1">
                <span class="opacity-50 group-hover:text-blue-600">→</span>
                <span class="ml-1 underline transition-colors group-hover:text-blue-600 underline-offset-4 decoration-1 decoration-black/30 group-hover:decoration-blue-600/50">{{ $heading['text'] }}</span>
            </a>

            @if (! empty($heading['children']))
                <x-table-of-contents.items :headings="$heading['children']" />
            @endif
        </li>
    @endforeach
</ul>
