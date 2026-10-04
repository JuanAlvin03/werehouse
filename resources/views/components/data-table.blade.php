@props(['headers' => [], 'rows' => null, 'emptyMessage' => 'No records found.'])

<div class="overflow-hidden rounded-xl border border-slate-200 bg-white shadow-sm">
    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-slate-200 text-left text-sm text-slate-700">
            <thead class="bg-slate-50 text-xs uppercase tracking-[0.08em] text-slate-500">
                <tr>
                    @foreach ($headers as $header)
                        <th scope="col" class="px-4 py-3 font-medium">{{ $header }}</th>
                    @endforeach
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-200 bg-white">
                @if ($rows && $rows->count())
                    {{ $slot }}
                @else
                    <tr>
                        <td colspan="{{ count($headers) }}" class="px-4 py-8 text-center text-sm text-slate-500">
                            {{ $emptyMessage }}
                        </td>
                    </tr>
                @endif
            </tbody>
        </table>
    </div>
</div>
