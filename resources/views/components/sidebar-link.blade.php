@props(['href', 'active' => false, 'icon' => null])

<a href="{{ $href }}" class="group flex items-center gap-3 rounded-lg px-3 py-2 text-sm font-medium transition {{ $active ? 'bg-slate-900 text-white shadow-sm' : 'text-slate-600 hover:bg-slate-100 hover:text-slate-900' }}">
    @if ($icon)
        <span class="{{ $active ? 'text-white' : 'text-slate-500 group-hover:text-slate-700' }}">
            {!! $icon !!}
        </span>
    @endif
    <span>{{ $slot }}</span>
</a>
