@props(['link'])

<div {{ $attributes }}>
    @if ($link->image_url)
        <a href="{{ $link->url }}" target="_blank">
            <img src="{{ $link->image_url }}" alt="{{ $link->title  }}" class="object-cover transition-opacity shadow-md shadow-black/5 rounded-xl aspect-video hover:opacity-50 ring-1 ring-black/5" />
        </a>
    @endif

    <div class="mt-4">
        <time datetime="{{ $link->is_approved }}">
            {{ $link->is_approved->isoFormat('LL') }}
        </time>

        <span class="inline-block mx-2 text-xs -translate-y-px opacity-50">•</span>

        <a href="{{ $link->user->github_data['user']['html_url'] }}" target="_blank" class="text-black underline underline-offset-4 decoration-black/30">
            {{ $link->user->name }}
        </a>
    </div>

    <div class="flex items-center justify-between gap-6 mt-2">
        <a href="{{ $link->url }}" target="_blank" class="font-bold transition-colors text-xl/tight hover:text-blue-600">
            {{ $link->title }}
        </a>

        <img
            src="{{ $link->user->avatar }}"
            alt="{{ $link->user->name }}"
            class="rounded-full ring-1 ring-black/5 size-10"
        />
    </div>

    <div class="mt-2">
        {!! Str::markdown($link->description ?? '') !!}
    </div>
</div>
