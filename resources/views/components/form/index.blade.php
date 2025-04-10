<form {{ $attributes }}>
    @csrf

    @method($method ?? 'GET')

    {{ $slot }}
</form>
