Category: {{ $category->name }}

---

Existing content: {{ $category->content }}

@if ($category->posts->isNotEmpty())
---

Existing posts: {{ $category->posts->pluck('title')->implode(', ') }}
@endif

@if ($additionalInstructions)
---

Additional instructions: {{ $additionalInstructions }}
@endif
