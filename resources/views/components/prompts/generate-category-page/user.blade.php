Category: {{ $category->name }}

---

Existing content: {{ $category->content }}

@if ($category->posts->isNotEmpty())
---

Existing posts:
@foreach ($category->posts as $post)
- [{{ $post->title }}]({{ route('posts.show', $post) }})
@endforeach
@endif

@if ($additionalInstructions)
---

Additional instructions: {{ $additionalInstructions }}
@endif
