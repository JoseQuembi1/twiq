<div 
    class="twiq-livewire-container {{ $position }}"
    wire:ignore
    x-data="twiqLivewireNotifications({{ json_encode($config) }})"
    x-init="init()"
>
    <div class="twiq-notifications" x-show="notifications.length > 0">
        <template x-for="notification in notifications" :key="notification.id">
            <div
                class="twiq-notification"
                :class="getNotificationClasses(notification)"
                x-show="notification.show"
                x-transition:enter="transition duration-300 ease-out"
                x-transition:enter-start="opacity-0 transform scale-90 translate-y-2"
                x-transition:enter-end="opacity-100 transform scale-100 translate-y-0"
                x-transition:leave="transition duration-300 ease-in"
                x-transition:leave-start="opacity-100 transform scale-100 translate-y-0"
                x-transition:leave-end="opacity-0 transform scale-90 translate-y-2"
                x-init="setupNotification(notification)"
            >
                <div class="twiq-notification-content">
                    <div class="twiq-notification-icon">
                        <svg class="w-5 h-5" :class="getIconClasses(notification)" fill="currentColor" viewBox="0 0 24 24">
                            <path x-show="notification.type === 'success'" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            <path x-show="notification.type === 'error'" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            <path x-show="notification.type === 'warning'" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.34 16.5c-.77.833.192 2.5 1.732 2.5z"/>
                            <path x-show="notification.type === 'info'" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                    <div class="twiq-notification-body">
                        <div x-show="notification.title" class="twiq-notification-title" x-text="notification.title"></div>
                        <div class="twiq-notification-message" x-text="notification.message"></div>
                    </div>
                    <button 
                        class="twiq-notification-close" 
                        @click="removeNotification(notification.id)"
                        aria-label="Close notification"
                    >
                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>
                </div>
                <div x-show="!notification.persistent && notification.duration > 0" class="twiq-notification-progress">
                    <div 
                        class="twiq-notification-progress-bar"
                        :class="getProgressClasses(notification)"
                        :style="{ animationDuration: notification.duration + 'ms' }"
                    ></div>
                </div>
            </div>
        </template>
    </div>
</div>

@push('scripts')
@once
<script>
document.addEventListener('alpine:init', () => {
    Alpine.data('twiqLivewireNotifications', (config) => ({
        notifications: @json($notifications),
        config: config,

        init() {
            // Listen for Livewire updates
            this.$wire.on('twiq', (data) => {
                this.addNotification(data);
            });

            // Sync with Livewire notifications
            this.$watch('$wire.notifications', (value) => {
                this.notifications = value.map(notification => ({
                    ...notification,
                    show: true
                }));
            });
        },

        addNotification(data) {
            const notification = {
                id: Date.now().toString(),
                type: data.type || 'info',
                message: data.message || '',
                title: data.title || null,
                persistent: data.persistent || false,
                duration: data.duration || this.config.types[data.type]?.duration || this.config.duration,
                show: true
            };

            this.notifications.push(notification);
            
            // Auto-remove if not persistent
            if (!notification.persistent && notification.duration > 0) {
                setTimeout(() => {
                    this.removeNotification(notification.id);
                }, notification.duration);
            }
        },

        removeNotification(id) {
            const index = this.notifications.findIndex(n => n.id === id);
            if (index > -1) {
                this.notifications[index].show = false;
                setTimeout(() => {
                    this.notifications.splice(index, 1);
                    this.$wire.call('removeNotification', id);
                }, 300);
            }
        },

        setupNotification(notification) {
            if (!notification.persistent && notification.duration > 0) {
                setTimeout(() => {
                    this.removeNotification(notification.id);
                }, notification.duration);
            }
        },

        getNotificationClasses(notification) {
            return `twiq-notification-${notification.type} ${notification.type}`;
        },

        getIconClasses(notification) {
            return `text-${this.config.types[notification.type]?.color || 'blue'}-500`;
        },

        getProgressClasses(notification) {
            return `bg-${this.config.types[notification.type]?.color || 'blue'}-500`;
        }
    }));
});
</script>
@endonce
@endpush