@props(['posts'])

<ul {{ $attributes->class('grid gap-10 gap-y-16 xl:gap-x-16 md:grid-cols-2 xl:grid-cols-3') }}>
    {{ $slot }}

    @foreach ($posts as $post)
        <li>
            <x-post :$post />
        </li>
    @endforeach
</ul>
