@props(['paginator'])

@if ($paginator->hasPages())
    <div {{ $attributes }}>
        {{ $paginator->links(attributes: ['class' => 'pagination']) }}
    </div>
@endif
