@props(['value'])

<label {{ $attributes->merge(['class' => 'block text-xs font-bold text-on-surface-variant uppercase tracking-wider mb-2']) }}>
    {{ $value ?? $slot }}
</label>
