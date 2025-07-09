<div
    wire:init="initialize"
    class="fixed z-50 pointer-events-none"
    :class="{
        'top-0 right-0': @js($position === 'top-right'),
        'top-0 left-0': @js($position === 'top-left'),
        'bottom-0 right-0': @js($position === 'bottom-right'),
        'bottom-0 left-0': @js($position === 'bottom-left'),
        'top-0 left-1/2 transform -translate-x-1/2': @js($position === 'top-center'),
        'bottom-0 left-1/2 transform -translate-x-1/2': @js($position === 'bottom-center'),
    }"
>
    @foreach ($notifications as $notification)
        <div
            wire:key="notification-{{ $notification['id'] }}"
            class="pointer-events-auto w-full max-w-sm overflow-hidden rounded-lg bg-white shadow-lg ring-1 ring-black ring-opacity-5 mt-2 dark:bg-gray-800"
            x-data="{ show: false }"
            x-show="show"
            x-init="setTimeout(() => { show = true }, 100)"
            x-transition:enter="transform ease-out duration-300 transition"
            x-transition:enter-start="translate-y-2 opacity-0 sm:translate-y-0 sm:translate-x-2"
            x-transition:enter-end="translate-y-0 opacity-100 sm:translate-x-0"
            x-transition:leave="transition ease-in duration-100"
            x-transition:leave-start="opacity-100"
            x-transition:leave-end="opacity-0"
        >
            <!-- Similar content to component view -->
        </div>
    @endforeach
</div>