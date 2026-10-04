@props(['label', 'value', 'tone' => 'default', 'icon' => null])

@php
    $toneStyles = [
        'default' => 'bg-white border-slate-200',
        'success' => 'bg-emerald-50 border-emerald-200',
        'warning' => 'bg-amber-50 border-amber-200',
        'danger' => 'bg-rose-50 border-rose-200',
    ];

    $iconStyles = [
        'default' => 'bg-slate-100 text-slate-600',
        'success' => 'bg-emerald-100 text-emerald-600',
        'warning' => 'bg-amber-100 text-amber-600',
        'danger' => 'bg-rose-100 text-rose-600',
    ];
@endphp

<div class="rounded-xl border {{ $toneStyles[$tone] ?? $toneStyles['default'] }} p-4 shadow-sm">
    <div class="flex items-center justify-between gap-4">
        <div>
            <p class="text-sm font-medium text-slate-500">{{ $label }}</p>
            <p class="mt-2 text-2xl font-semibold tracking-tight text-slate-900">{{ $value }}</p>
        </div>

        @if ($icon)
            <div class="flex h-11 w-11 items-center justify-center rounded-lg {{ $iconStyles[$tone] ?? $iconStyles['default'] }}">
                {!! $icon !!}
            </div>
        @endif
    </div>
</div>
