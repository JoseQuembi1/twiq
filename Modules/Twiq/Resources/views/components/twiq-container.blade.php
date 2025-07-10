<div
    x-data="twiqContainer(@js($config))"
    class="fixed z-50 pointer-events-none"
    :class="{
        'top-0 right-0 p-4 sm:p-6': position === 'top-right',
        'top-0 left-0 p-4 sm:p-6': position === 'top-left',
        'bottom-0 right-0 p-4 sm:p-6': position === 'bottom-right',
        'bottom-0 left-0 p-4 sm:p-6': position === 'bottom-left',
        'top-0 left-1/2 -translate-x-1/2 p-4': position === 'top-center',
        'bottom-0 left-1/2 -translate-x-1/2 p-4': position === 'bottom-center'
    }"
>
    {{-- Container com Grid Responsivo --}}
    <div 
        class="space-y-2 sm:space-y-3"
        :class="{
            'max-w-[calc(100vw-2rem)] sm:max-w-md': maxWidth === 'md',
            'max-w-[calc(100vw-2rem)] sm:max-w-lg': maxWidth === 'lg',
            'max-w-[calc(100vw-2rem)] sm:max-w-xl': maxWidth === 'xl'
        }"
    >
        <template x-for="notification in notifications" :key="notification.id">
            <div 
                x-show="notification.visible"
                class="transform transition-all duration-500 ease-out"
                :class="{
                    'translate-x-0 opacity-100': notification.visible,
                    'translate-x-full opacity-0': !notification.visible && position.includes('right'),
                    '-translate-x-full opacity-0': !notification.visible && position.includes('left'),
                    'translate-y-full opacity-0': !notification.visible && position.includes('bottom'),
                    '-translate-y-full opacity-0': !notification.visible && position.includes('top')
                }"
            >
                <x-twiq-notification 
                    :id="notification.id"
                    :type="notification.type"
                    :message="notification.message"
                    :title="notification.title"
                    :duration="notification.duration"
                    :persistent="notification.persistent"
                    :position="position"
                    :icon="notification.icon"
                    :sound="notification.sound"
                    :group="notification.group"
                />
            </div>
        </template>
    </div>
</div>