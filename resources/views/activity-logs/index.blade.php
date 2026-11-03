<x-wms-layout title="Activity Logs">
    <x-page-header title="Activity Logs" subtitle="List of all activity logs">
    </x-page-header>

    <x-panel>
        @if ($activityLogs->count())
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-slate-200 text-left text-sm text-slate-700">
                    <thead class="bg-slate-50 text-xs uppercase tracking-[0.12em] text-slate-500">
                        <tr>
                            <th class="px-4 py-3 font-medium">Log ID</th>
                            <th class="px-4 py-3 font-medium">Log Name</th>
                            <th class="px-4 py-3 font-medium">Subject ID</th>
                            <th class="px-4 py-3 font-medium">Description</th>
                            <th class="px-4 py-3 font-medium">User</th>
                            <th class="px-4 py-3 font-medium text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-200 bg-white">
                        @foreach ($activityLogs as $activityLog)
                            <tr>
                                <td class="px-4 py-3 font-medium text-slate-800">{{ $activityLog->id }}</td>
                                <td class="px-4 py-3">{{ $activityLog->log_name }}</td>
                                <td class="px-4 py-3">{{ $activityLog->subject_id }}</td>
                                <td class="px-4 py-3">{{ $activityLog->description }}</td>
                                <td class="px-4 py-3">{{ $activityLog->causer?->name ?? 'N/A' }}</td>
                                <td class="px-4 py-3 text-right">
                                    <div class="flex justify-end gap-2">
                                        <a href="{{ route('activity-logs.show', $activityLog) }}" class="text-sm font-medium text-slate-700 hover:text-slate-900">View</a>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="mt-4">
                {{ $activityLogs->links() }}
            </div>
        @else
            <p class="text-sm text-slate-500">No activity logs found.</p>
        @endif
    </x-panel>
</x-wms-layout>
