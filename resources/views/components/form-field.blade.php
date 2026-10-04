@props(['label', 'for' => null, 'required' => false, 'help' => null])

<div class="space-y-2">
    @if ($label)
        <label for="{{ $for }}" class="block text-sm font-medium text-slate-700">
            {{ $label }}
            @if ($required)
                <span class="text-rose-500">*</span>
            @endif
        </label>
    @endif

    {{ $slot }}

    @if ($help)
        <p class="text-xs text-slate-500">{{ $help }}</p>
    @endif
</div>
