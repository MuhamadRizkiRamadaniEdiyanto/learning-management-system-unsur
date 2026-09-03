<div x-data="{
    toasts: [],
    addToast(detail) {
        const toast = {
            id: Date.now() + Math.random(),
            message: detail.message || 'Operasi berhasil.',
            type: detail.type || 'success',
        };
        this.toasts.push(toast);
        window.setTimeout(() => {
            this.toasts = this.toasts.filter(item => item.id !== toast.id);
        }, 3000);
    },
}" x-on:notify.window="addToast($event.detail)" @if (Session::has('success'))
    x-init="addToast({ message: @js(Session::get('success')), type: 'success' })"
@elseif (Session::has('error'))
    x-init="addToast({ message: @js(Session::get('error')), type: 'error' })"
@elseif ($errors->any())
    x-init="addToast({ message: @js($errors->first()), type: 'error' })"
    @endif
    class="pointer-events-none fixed right-4 top-4 z-[100] flex w-[calc(100%-2rem)] max-w-sm flex-col gap-3 sm:right-6 sm:top-6"
    aria-live="polite"
    >
    <style>
        [x-cloak] {
            display: none !important;
        }
    </style>
    <script>
        window.showToast = function(message, type = 'success') {
            window.dispatchEvent(new CustomEvent('notify', {
                detail: {
                    message,
                    type
                }
            }));
        };
    </script>

    <template x-for="toast in toasts" :key="toast.id">
        <div x-cloak x-transition:enter="transform ease-out duration-300 transition"
            x-transition:enter-start="translate-y-2 opacity-0 sm:translate-x-4 sm:translate-y-0"
            x-transition:enter-end="translate-y-0 opacity-100 sm:translate-x-0"
            x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100"
            x-transition:leave-end="opacity-0"
            class="pointer-events-auto flex items-start gap-3 rounded-xl border bg-white p-4 shadow-lg"
            :class="toast.type === 'error' ? 'border-rose-200' : 'border-emerald-200'" role="status">
            <span class="flex h-6 w-6 shrink-0 items-center justify-center rounded-full text-sm font-bold"
                :class="toast.type === 'error' ? 'bg-rose-100 text-rose-700' : 'bg-emerald-100 text-emerald-700'"
                x-text="toast.type === 'error' ? '!' : '\u2713'"></span>
            <p class="pt-0.5 text-sm font-medium text-slate-700" x-text="toast.message"></p>
            <button type="button" class="ml-auto shrink-0 text-slate-400 hover:text-slate-700"
                x-on:click="toasts = toasts.filter(item => item.id !== toast.id)"
                aria-label="Tutup notifikasi">&times;</button>
        </div>
    </template>
</div>
