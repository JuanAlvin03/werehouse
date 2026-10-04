<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <title>{{ config('app.name', 'Warehouse System') }}</title>
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="min-h-screen bg-slate-100 font-sans text-slate-800 antialiased">
        <div class="mx-auto flex min-h-screen max-w-6xl items-center px-4 py-10 sm:px-6 lg:px-8">
            <div class="grid w-full overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm lg:grid-cols-[1.2fr_0.8fr]">
                <div class="p-8 sm:p-10 lg:p-12">
                    <p class="text-xs font-medium uppercase tracking-[0.18em] text-slate-500">Warehouse management</p>
                    <h1 class="mt-4 text-4xl font-semibold tracking-tight text-slate-900 sm:text-5xl">Control stock with clarity.</h1>
                    <p class="mt-5 max-w-xl text-base text-slate-600">
                        Manage products, inventory, locations, and transactions in a single operational dashboard built for daily warehouse workflows.
                    </p>

                    <div class="mt-8 flex flex-wrap items-center gap-3">
                        @if (Route::has('login'))
                            <a href="{{ route('login') }}" class="inline-flex items-center justify-center rounded-lg bg-slate-900 px-5 py-3 text-sm font-medium text-white transition hover:bg-slate-700">
                                Sign in
                            </a>
                        @endif
                    </div>

                    <dl class="mt-10 grid gap-4 sm:grid-cols-3">
                        <div class="rounded-xl border border-slate-200 bg-slate-50 p-4">
                            <dt class="text-xs uppercase tracking-[0.12em] text-slate-500">Inventory</dt>
                            <dd class="mt-2 text-2xl font-semibold text-slate-900">Live</dd>
                        </div>
                        <div class="rounded-xl border border-slate-200 bg-slate-50 p-4">
                            <dt class="text-xs uppercase tracking-[0.12em] text-slate-500">Transfers</dt>
                            <dd class="mt-2 text-2xl font-semibold text-slate-900">Fast</dd>
                        </div>
                        <div class="rounded-xl border border-slate-200 bg-slate-50 p-4">
                            <dt class="text-xs uppercase tracking-[0.12em] text-slate-500">Audit</dt>
                            <dd class="mt-2 text-2xl font-semibold text-slate-900">Trace</dd>
                        </div>
                    </dl>
                </div>

                <div class="bg-slate-900 p-8 text-white sm:p-10 lg:p-12">
                    <div class="rounded-2xl border border-slate-700 bg-slate-800 p-5">
                        <p class="text-xs uppercase tracking-[0.18em] text-slate-300">Operations</p>
                        <div class="mt-6 space-y-4">
                            <div class="rounded-lg bg-slate-700/80 p-4">
                                <p class="text-sm text-slate-300">Inbound</p>
                                <p class="mt-2 text-xl font-semibold">Goods receipts</p>
                            </div>
                            <div class="rounded-lg bg-slate-700/80 p-4">
                                <p class="text-sm text-slate-300">Outbound</p>
                                <p class="mt-2 text-xl font-semibold">Stock issues</p>
                            </div>
                            <div class="rounded-lg bg-slate-700/80 p-4">
                                <p class="text-sm text-slate-300">Inventory</p>
                                <p class="mt-2 text-xl font-semibold">Adjustments & transfers</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </body>
</html>
