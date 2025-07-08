// Twiq Notifications Alpine.js Component
document.addEventListener('alpine:init', () => {
    Alpine.data('twiqNotifications', (config) => ({
        notifications: [],
        config: config,
        duplicateMap: new Map(),

        init() {
            // Listen for global twiq events
            window.addEventListener('twiq', (event) => {
                this.addNotification(event.detail);
            });

            // Listen for Livewire dispatched events
            document.addEventListener('twiq', (event) => {
                this.addNotification(event.detail);
            });

            // Dark mode detection
            if (this.config.dark_mode === 'auto') {
                this.initDarkMode();
            }

            // Initialize sound if enabled
            if (this.config.sound) {
                this.initSound();
            }
        },

        addNotification(data) {
            const notification = {
                id: this.generateId(),
                type: data.type || 'info',
                message: data.message || '',
                title: data.title || null,
                persistent: data.persistent || false,
                duration: data.duration || this.config.types[data.type]?.duration || this.config.duration,
                show: true,
                timestamp: Date.now()
            };

            // Prevent duplicates
            if (this.config.prevent_duplicates) {
                const duplicateKey = `${notification.type}:${notification.message}`;
                if (this.duplicateMap.has(duplicateKey)) {
                    return;
                }
                this.duplicateMap.set(duplicateKey, notification.id);
                
                // Clean up after duration
                setTimeout(() => {
                    this.duplicateMap.delete(duplicateKey);
                }, notification.duration + 1000);
            }

            // Group similar notifications
            if (this.config.grouping.enabled) {
                const similar = this.notifications.find(n => 
                    n.type === notification.type && 
                    n.message === notification.message &&
                    (Date.now() - n.timestamp) < this.config.grouping.timeout
                );
                
                if (similar) {
                    similar.count = (similar.count || 1) + 1;
                    similar.timestamp = Date.now();
                    return;
                }
            }

            // Enforce max notifications
            if (this.notifications.length >= this.config.max_notifications) {
                this.notifications.shift();
            }

            this.notifications.push(notification);
            
            // Play sound if enabled
            if (this.config.sound) {
                this.playSound(notification.type);
            }
        },

        removeNotification(id) {
            const index = this.notifications.findIndex(n => n.id === id);
            if (index > -1) {
                this.notifications[index].show = false;
                setTimeout(() => {
                    this.notifications.splice(index, 1);
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
            let classes = `twiq-notification-${notification.type} ${notification.type}`;
            
            if (notification.count && notification.count > 1) {
                classes += ' grouped';
            }
            
            return classes;
        },

        getIconClasses(notification) {
            const color = this.config.types[notification.type]?.color || 'blue';
            return `text-${color}-500`;
        },

        getProgressClasses(notification) {
            const color = this.config.types[notification.type]?.color || 'blue';
            return `bg-${color}-500`;
        },

        generateId() {
            return Math.random().toString(36).substr(2, 9);
        },

        initDarkMode() {
            const darkModeQuery = window.matchMedia('(prefers-color-scheme: dark)');
            
            const updateDarkMode = () => {
                if (darkModeQuery.matches) {
                    document.documentElement.classList.add('dark');
                } else {
                    document.documentElement.classList.remove('dark');
                }
            };
            
            updateDarkMode();
            darkModeQuery.addEventListener('change', updateDarkMode);
        },

        initSound() {
            this.sounds = {
                success: this.createSound(800, 0.1, 'sine'),
                error: this.createSound(400, 0.15, 'triangle'),
                warning: this.createSound(600, 0.1, 'square'),
                info: this.createSound(500, 0.1, 'sine')
            };
        },

        createSound(frequency, duration, type = 'sine') {
            return () => {
                if (!window.AudioContext && !window.webkitAudioContext) return;
                
                const context = new (window.AudioContext || window.webkitAudioContext)();
                const oscillator = context.createOscillator();
                const gainNode = context.createGain();
                
                oscillator.connect(gainNode);
                gainNode.connect(context.destination);
                
                oscillator.frequency.value = frequency;
                oscillator.type = type;
                
                gainNode.gain.setValueAtTime(0.1, context.currentTime);
                gainNode.gain.exponentialRampToValueAtTime(0.01, context.currentTime + duration);
                
                oscillator.start(context.currentTime);
                oscillator.stop(context.currentTime + duration);
            };
        },

        playSound(type) {
            if (this.sounds && this.sounds[type]) {
                this.sounds[type]();
            }
        }
    }));
});

// Global helper function for easy usage
window.twiq = {
    notify(type, message, options = {}) {
        const event = new CustomEvent('twiq', {
            detail: {
                type,
                message,
                ...options
            }
        });
        window.dispatchEvent(event);
    },

    success(message, options = {}) {
        this.notify('success', message, options);
    },

    error(message, options = {}) {
        this.notify('error', message, options);
    },

    warning(message, options = {}) {
        this.notify('warning', message, options);
    },

    info(message, options = {}) {
        this.notify('info', message, options);
    }
};