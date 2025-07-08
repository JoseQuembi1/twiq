<div 
    class="twiq-container {{ $position }}"
    x-data="twiqNotifications({{ json_encode($config) }})"
    x-init="init()"
    @twiq.window="addNotification($event.detail)"
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

<style>
.twiq-container {
    position: fixed;
    z-index: 9999;
    pointer-events: none;
}

.twiq-container.top-right {
    top: 1rem;
    right: 1rem;
}

.twiq-container.top-left {
    top: 1rem;
    left: 1rem;
}

.twiq-container.bottom-right {
    bottom: 1rem;
    right: 1rem;
}

.twiq-container.bottom-left {
    bottom: 1rem;
    left: 1rem;
}

.twiq-container.top-center {
    top: 1rem;
    left: 50%;
    transform: translateX(-50%);
}

.twiq-container.bottom-center {
    bottom: 1rem;
    left: 50%;
    transform: translateX(-50%);
}

.twiq-notifications {
    display: flex;
    flex-direction: column;
    gap: 0.5rem;
    max-width: 400px;
}

.twiq-notification {
    pointer-events: auto;
    background: white;
    border-radius: 0.5rem;
    box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
    border: 1px solid #e5e7eb;
    overflow: hidden;
    position: relative;
}

.dark .twiq-notification {
    background: #1f2937;
    border-color: #374151;
}

.twiq-notification-content {
    display: flex;
    align-items: flex-start;
    padding: 1rem;
    gap: 0.75rem;
}

.twiq-notification-icon {
    flex-shrink: 0;
    margin-top: 0.125rem;
}

.twiq-notification-body {
    flex: 1;
    min-width: 0;
}

.twiq-notification-title {
    font-weight: 600;
    color: #111827;
    margin-bottom: 0.25rem;
    font-size: 0.875rem;
}

.dark .twiq-notification-title {
    color: #f9fafb;
}

.twiq-notification-message {
    color: #6b7280;
    font-size: 0.875rem;
    line-height: 1.25rem;
}

.dark .twiq-notification-message {
    color: #d1d5db;
}

.twiq-notification-close {
    flex-shrink: 0;
    padding: 0.25rem;
    border-radius: 0.375rem;
    color: #6b7280;
    transition: all 0.2s;
    margin-top: -0.25rem;
    margin-right: -0.25rem;
}

.twiq-notification-close:hover {
    background: #f3f4f6;
    color: #374151;
}

.dark .twiq-notification-close:hover {
    background: #374151;
    color: #d1d5db;
}

.twiq-notification-progress {
    position: absolute;
    bottom: 0;
    left: 0;
    right: 0;
    height: 3px;
    background: #f3f4f6;
}

.dark .twiq-notification-progress {
    background: #374151;
}

.twiq-notification-progress-bar {
    height: 100%;
    width: 100%;
    transform-origin: left;
    animation: twiq-progress linear;
}

@keyframes twiq-progress {
    from {
        transform: scaleX(1);
    }
    to {
        transform: scaleX(0);
    }
}

/* Type specific colors */
.twiq-notification.success .twiq-notification-icon {
    color: #10b981;
}

.twiq-notification.success .twiq-notification-progress-bar {
    background: #10b981;
}

.twiq-notification.error .twiq-notification-icon {
    color: #ef4444;
}

.twiq-notification.error .twiq-notification-progress-bar {
    background: #ef4444;
}

.twiq-notification.warning .twiq-notification-icon {
    color: #f59e0b;
}

.twiq-notification.warning .twiq-notification-progress-bar {
    background: #f59e0b;
}

.twiq-notification.info .twiq-notification-icon {
    color: #3b82f6;
}

.twiq-notification.info .twiq-notification-progress-bar {
    background: #3b82f6;
}

@media (max-width: 640px) {
    .twiq-container {
        left: 1rem !important;
        right: 1rem !important;
        transform: none !important;
    }
    
    .twiq-notifications {
        max-width: none;
    }
}
</style>