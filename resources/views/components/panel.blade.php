@props(['title' => null, 'subtitle' => null])

<div class="rounded-xl border border-slate-200 bg-white shadow-sm">
    @if ($title || $subtitle)
        <div class="border-b border-slate-200 px-5 py-4">
            @if ($title)
                <h2 class="text-base font-semibold text-slate-900">{{ $title }}</h2>
            @endif
            @if ($subtitle)
                <p class="mt-1 text-sm text-slate-500">{{ $subtitle }}</p>
            @endif
        </div>
    @endif

    <div class="p-5">
        {{ $slot }}
    </div>
</div>
