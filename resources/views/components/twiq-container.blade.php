<div
    x-data="twiqContainer(@js([
        'position' => $position,
        'maxNotifications' => $maxNotifications,
        'sound' => $sound,
        'darkMode' => $darkMode
    ]))"
    x-init="initialize()"
    @class([
        'fixed z-50 pointer-events-none',
        'top-0 right-0' => $position === 'top-right',
        'top-0 left-0' => $position === 'top-left',
        'bottom-0 right-0' => $position === 'bottom-right',
        'bottom-0 left-0' => $position === 'bottom-left',
        'top-0 left-1/2 transform -translate-x-1/2' => $position === 'top-center',
        'bottom-0 left-1/2 transform -translate-x-1/2' => $position === 'bottom-center',
    ])
    style="width: min(calc(100% - 2rem), 24rem);"
>
    <template x-for="notification in notifications" :key="notification.id">
        <div
            x-show="notification.visible"
            x-transition:enter="transform ease-out duration-300 transition"
            x-transition:enter-start="translate-y-2 opacity-0 sm:translate-y-0 sm:translate-x-2"
            x-transition:enter-end="translate-y-0 opacity-100 sm:translate-x-0"
            x-transition:leave="transition ease-in duration-100"
            x-transition:leave-start="opacity-100"
            x-transition:leave-end="opacity-0"
            @class([
                'pointer-events-auto w-full max-w-sm overflow-hidden rounded-lg shadow-lg ring-1 ring-black ring-opacity-5 mt-2',
                'dark:bg-gray-800' => $darkMode === 'dark',
            ])
            :class="notification.classes"
        >
            <div class="p-4">
                <div class="flex items-start">
                    <div class="flex-shrink-0">
                        <template x-if="notification.icon">
                            <div :class="notification.iconClasses">
                                <i :class="notification.icon"></i>
                            </div>
                        </template>
                    </div>
                    <div class="ml-3 w-0 flex-1">
                        <template x-if="notification.title">
                            <p class="text-sm font-medium text-gray-900 dark:text-white" x-text="notification.title"></p>
                        </template>
                        <p class="mt-1 text-sm text-gray-500 dark:text-gray-400" x-text="notification.message"></p>
                    </div>
                    <div class="ml-4 flex flex-shrink-0">
                        <button
                            type="button"
                            class="inline-flex rounded-md text-gray-400 hover:text-gray-500 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2"
                            @click="removeNotification(notification.id)"
                        >
                            <span class="sr-only">Close</span>
                            <svg class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd" />
                            </svg>
                        </button>
                    </div>
                </div>
            </div>
            <div
                x-show="notification.progress"
                class="h-1 bg-gradient-to-r"
                :class="notification.progressClasses"
                :style="{ width: notification.progress + '%' }"
            ></div>
        </div>
    </template>
</div>