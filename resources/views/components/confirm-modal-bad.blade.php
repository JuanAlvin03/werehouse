@props([
    'id' => 'confirm-modal',
    'title' => 'Confirm action',
    'message' => 'Are you sure?',
    'confirmText' => 'Confirm',
    'cancelText' => 'Cancel',
    'confirmClass' => 'bg-slate-900 text-white hover:bg-slate-800',
])
     
<div
    x-data="{
        open: false,
        formAction: '',
        submit() {
            if (this.formAction) {
                const form = document.getElementById(this.formAction);
                if (form) {
                    form.submit();
                    return;
                }
            }
                
            const btn = document.getElementById(this.formAction);
            if (btn && btn.dataset.actionUrl) {
                window.location.href = btn.dataset.actionUrl;
            }
        }
    }"
    
    x-on:open-confirm-modal.window="open = true; formAction = $event.detail.id;"
    x-on:close-confirm-modal.window="open = false; formAction = '';"
    x-show="open"
    x-cloak
    class="fixed inset-0 z-50 hidden overflow-y-auto"
    :class="{ '!block': open }"
    aria-modal="true"
    role="dialog"
>
    
    <div class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm" x-on:click="open = false"></div>

    <div class="relative flex min-h-full items-center justify-center p-4">
    
        <div class="w-full max-w-md rounded-2xl border border-slate-200 bg-white p-6 shadow-2xl">
            <div class="mb-4 flex items-start justify-between gap-3">
                <div>
                    <h3 class="text-lg font-semibold text-slate-900">{{ $title }}</h3>
                </div>
                <button
                    type="button"
                    x-on:click="open = false"
                    class="inline-flex h-8 w-8 items-center justify-center rounded-full text-slate-400 transition hover:bg-slate-100 hover:text-slate-600"
                    aria-label="Close dialog"
                >
                    <svg xmlns="http://www.w3.org/2000/svg\" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
            <p class="text-sm leading-6 text-slate-600">{{ $message }}</p>

            <div class="mt-6 flex justify-end gap-3">
                <button
                    type="button"
                    x-on:click="open = false"
                    class="inline-flex items-center justify-center rounded-lg border border-slate-300 bg-white px-4 py-2.5 text-sm font-medium text-slate-700 transition hover:bg-slate-50"
                >
                    {{ $cancelText }}
                </button>
                <button 
                    type="button"
                    x-on:click="submit()"
                    class="inline-flex items-center justify-center rounded-lg px-4 py-2.5 text-sm font-medium transition {{ $confirmClass }}"
                >
                    {{ $confirmText }}
                </button>
            </div>
        </div>
    </div>
</div>