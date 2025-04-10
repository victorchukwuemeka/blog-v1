<div {{ $attributes->only('class') }}>
    @if (! empty($label))
        <label for="{{ $id }}" class="">{{ $label }}@if (! empty($required))*@endif</label>
    @endif

    <textarea
        id="{{ $id }}"
        {{ $attributes->except(['class', 'id', 'type', 'value'])->class('') }}
    >{{ $value ?? '' }}</textarea>

    @if (! empty($wireModel) || ! empty($name))
        @error($wireModel ?? $name)
            <div class="">{{ $message }}</div>
        @enderror
    @endif
</div>
