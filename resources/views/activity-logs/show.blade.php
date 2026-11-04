<x-wms-layout :title="'Activity Log'">

    <div class="grid gap-6 lg:grid-cols-4">
        <!-- Main Content -->
        <div class="lg:col-span-4 space-y-6">
            <!-- Document Header -->
            <x-panel title="Log Details">
                <dl class="space-y-4 text-sm text-slate-700">
                    <div class="flex justify-between gap-4 border-b border-slate-200 pb-3">
                        <dt class="text-slate-500">ID</dt>
                        <dd class="font-medium text-slate-900">{{ $activityLog->id }}</dd>
                    </div>
                    <div class="flex justify-between gap-4 border-b border-slate-200 pb-3">
                        <dt class="text-slate-500">Data</dt>
                        <dd class="font-medium text-slate-900">{{ $activityLog->log_name }}</dd>
                    </div>                   
                    <div class="flex justify-between gap-4 border-b border-slate-200 pb-3">
                        <dt class="text-slate-500">User</dt>
                        <dd class="font-medium text-slate-900">{{ $activityLog->causer?->name ?? 'N/A' }}</dd>
                    </div>
                    <div class="flex justify-between gap-4 border-b border-slate-200 pb-3">
                        <dt class="text-slate-500">Time</dt>
                        <dd class="font-medium text-slate-900">{{ $activityLog->created_at->format('M d, Y g:i A') }}</dd>
                    </div>
                    <div class="flex justify-between gap-4 border-b border-slate-200 pb-3">
                        <dt class="text-slate-500">Event</dt>
                        <dd class="font-medium text-slate-900">{{ $activityLog->event }}</dd>
                    </div>    
                </dl>
            </x-panel>
        </div>

        <div class="lg:col-span-2 space-y-6">
            <!-- Old -->
            <x-panel title="Old">
                @if (isset($properties->old))
                    @foreach ($properties->old as $key => $value)
                        @if ($key != 'created_at' && $key != 'updated_at')
                            <li class="d-flex align-items-center py-2">
                                <span class="bullet me-5"></span> {{ $key }} : {{ $value }}
                            </li>
                        @endif
                    @endforeach
                @endif
            </x-panel>
        </div>

        <div class="lg:col-span-2 space-y-6">
            <!-- New -->
            <x-panel title="New">
                @if (isset($properties->attributes))
                    @foreach ($properties->attributes as $key => $value)
                        @if ($key != 'created_at' && $key != 'updated_at')
                            <li class="d-flex align-items-center py-2">
                                <span class="bullet me-5"></span> {{ $key }} : {{ $value }}
                            </li>
                        @endif
                    @endforeach
                @endif
            </x-panel>
        </div>
    </div>
</x-wms-layout>
