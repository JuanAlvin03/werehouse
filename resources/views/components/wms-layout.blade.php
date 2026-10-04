@props(['title' => null])

<x-app-shell :title="$title">
    <div class="min-h-screen bg-slate-100 text-slate-900">
        <div class="mx-auto flex max-w-7xl flex-col gap-6 px-4 py-5 sm:px-6 lg:flex-row lg:px-8">
            <aside class="w-full shrink-0 lg:w-72">
                <div class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">
                    <div class="border-b border-slate-200 px-5 py-4">
                        <div class="flex items-center gap-3">
                            <div class="inline-flex h-10 w-10 items-center justify-center rounded-xl bg-slate-900 text-sm font-bold text-white">WM</div>
                            <div>
                                <p class="text-xs uppercase tracking-[0.15em] text-slate-500">Warehouse</p>
                                <p class="text-base font-semibold text-slate-900">Control</p>
                            </div>
                        </div>
                    </div>

                    <nav class="space-y-2 p-3">
                        <x-sidebar-link :href="route('dashboard')" :active="request()->routeIs('dashboard')" icon='<svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.7" d="M3 12.75L12 4l9 8.75M5 10.5V20h14v-9.5" /></svg>'>Dashboard</x-sidebar-link>

                        <x-sidebar-link :href="route('documents.index')" :active="request()->routeIs('documents.*')" icon='<svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.7" d="M9 7.5h6M9 12h6m-6 4.5h6M5.5 4.5h13a1.5 1.5 0 0 1 1.5 1.5v12a1.5 1.5 0 0 1-1.5 1.5h-13A1.5 1.5 0 0 1 4 18V6a1.5 1.5 0 0 1 1.5-1.5z" /></svg>'>Documents</x-sidebar-link>

                        <x-sidebar-link :href="route('products.index')" :active="request()->routeIs('products.*')" icon='<svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.7" d="M4 7.5 12 3l8 4.5v9L12 21l-8-4.5v-9zm8 3.5 8-4.5M12 11v10" /></svg>'>Products</x-sidebar-link>

                        <x-sidebar-link :href="route('warehouses.index')" :active="request()->routeIs('warehouses.*')" icon='<svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.7" d="M3.5 20V8.5L12 4l8.5 4.5V20M7 10h10M7 14h10M7 18h10" /></svg>'>Warehouses</x-sidebar-link>

                        <x-sidebar-link :href="route('business-partners.index')" :active="request()->routeIs('business-partners.*')" icon='<svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.7" d="M16 19v-1a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v1M12 11a4 4 0 1 0 0-8 4 4 0 0 0 0 8zm8 8v-1a4 4 0 0 0-3-3.87M17 3.13a4 4 0 0 1 0 7.75" /></svg>'>Partners</x-sidebar-link>

                        <x-sidebar-link :href="route('product-categories.index')" :active="request()->routeIs('product-categories.*')" icon='<svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.7" d="M4 6h16M4 12h16M4 18h10" /></svg>'>Categories</x-sidebar-link>

                        <x-sidebar-link :href="route('units.index')" :active="request()->routeIs('units.*')" icon='<svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.7" d="M12 2v20M4 6.5h16M4 17.5h16" /></svg>'>Units</x-sidebar-link>
                        
                        <x-sidebar-link :href="route('profile.edit')" :active="request()->routeIs('profile.*')" icon='<svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.7" d="M12 2.75a4.75 4.75 0 0 1 4.75 4.75v.5A4.75 4.75 0 0 1 12 13.75a4.75 4.75 0 0 1-4.75-4.75v-.5A4.75 4.75 0 0 1 12 2.75zm-7.25 17c.9-2.98 3.53-5 7.25-5s6.35 2.02 7.25 5" /></svg>'>Profile</x-sidebar-link>
                    </nav>
                </div>
            </aside>

            <main class="flex-1">
                <div class="rounded-2xl border border-slate-200 bg-white shadow-sm">
                    <div class="flex items-center justify-between border-b border-slate-200 px-5 py-4">
                        <div>
                            <p class="text-xs uppercase tracking-[0.15em] text-slate-500">Operations</p>
                            <h1 class="mt-1 text-lg font-semibold text-slate-900">{{ $title ?? 'Warehouse System' }}</h1>
                        </div>

                        <div class="flex items-center gap-3">
                            <span class="hidden rounded-full bg-slate-100 px-2.5 py-1 text-xs font-medium text-slate-600 sm:inline-flex">{{ auth()->user()->name ?? 'System User' }}</span>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="rounded-lg border border-slate-300 px-3 py-2 text-sm font-medium text-slate-700 transition hover:bg-slate-50">
                                    Log out
                                </button>
                            </form>
                        </div>
                    </div>

                    <div class="p-5 sm:p-6">
                        {{ $slot }}
                    </div>
                </div>
            </main>
        </div>
    </div>
</x-app-shell>
