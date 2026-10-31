<x-wms-layout title="Users">
    <x-page-header title="Users" subtitle="Users that can access this website">
        <x-slot:actions>
            <a href="{{ route('users.create') }}" class="inline-flex items-center justify-center rounded-lg bg-slate-900 px-4 py-2.5 text-sm font-medium text-white transition hover:bg-slate-700">New user</a>
        </x-slot:actions>
    </x-page-header>

    <x-panel>
        @if ($users->count())
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-slate-200 text-left text-sm text-slate-700">
                    <thead class="bg-slate-50 text-xs uppercase tracking-[0.12em] text-slate-500">
                        <tr>
                            <th class="px-4 py-3 font-medium">ID</th>
                            <th class="px-4 py-3 font-medium">Name</th>
                            <th class="px-4 py-3 font-medium">Email</th>
                            <th class="px-4 py-3 font-medium text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-200 bg-white">
                        @foreach ($users as $user)
                            <tr>
                                <td class="px-4 py-3 font-medium text-slate-800">{{ $user->id }}</td>
                                <td class="px-4 py-3">{{ $user->name }}</td>
                                <td class="px-4 py-3">{{ $user->email }}</td>
                                <td class="px-4 py-3 text-right">
                                    <div class="flex justify-end gap-2">
                                        {{--  <a href="{{ route('users.show', $user) }}" class="text-sm font-medium text-slate-700 hover:text-slate-900">View</a>
                                        <a href="{{ route('users.edit', $user) }}" class="text-sm font-medium text-slate-700 hover:text-slate-900">Edit</a>--}}
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            {{-- if paginate later
            <div class="mt-4">
                {{ $users->links() }}
            </div> --}}
        @else
            <p class="text-sm text-slate-500">No users found.</p>
        @endif
    </x-panel>
</x-wms-layout>
