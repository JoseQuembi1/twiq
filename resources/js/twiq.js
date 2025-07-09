document.addEventListener('alpine:init', () => {
    Alpine.data('twiqContainer', (config) => ({
        notifications: [],
        soundEnabled: config.sound || false,
        audioContext: null,

        initialize() {
            // Inicializar Web Audio API se o som estiver habilitado
            if (this.soundEnabled) {
                this.initializeAudio();
            }

            // Escutar eventos de notificação
            window.addEventListener('twiq', (event) => {
                this.addNotification(event.detail);
            });

            // Escutar eventos de limpeza
            window.addEventListener('twiq:clear', () => {
                this.clearNotifications();
            });

            // Configurar modo escuro
            this.setupDarkMode(config.darkMode);
        },

        initializeAudio() {
            try {
                this.audioContext = new (window.AudioContext || window.webkitAudioContext)();
            } catch (e) {
                console.warn('Web Audio API não suportada');
            }
        },

        playNotificationSound(type) {
            if (!this.audioContext) return;

            const oscillator = this.audioContext.createOscillator();
            const gainNode = this.audioContext.createGain();

            oscillator.connect(gainNode);
            gainNode.connect(this.audioContext.destination);

            // Diferentes sons para diferentes tipos
            const sounds = {
                success: { frequency: 880, duration: 0.1 },
                error: { frequency: 220, duration: 0.2 },
                warning: { frequency: 440, duration: 0.15 },
                info: { frequency: 660, duration: 0.1 }
            };

            const sound = sounds[type] || sounds.info;

            oscillator.frequency.value = sound.frequency;
            gainNode.gain.value = 0.1;

            oscillator.start();
            oscillator.stop(this.audioContext.currentTime + sound.duration);
        },

        setupDarkMode(mode) {
            if (mode === 'auto') {
                // Observar mudanças no modo escuro do sistema
                const darkModeMediaQuery = window.matchMedia('(prefers-color-scheme: dark)');
                darkModeMediaQuery.addEventListener('change', (e) => {
                    document.documentElement.classList.toggle('dark', e.matches);
                });
                
                // Configuração inicial
                document.documentElement.classList.toggle('dark', darkModeMediaQuery.matches);
            } else if (mode === 'dark') {
                document.documentElement.classList.add('dark');
            }
        },

        addNotification(notification) {
            const id = notification.id || this.generateId();
            
            const formattedNotification = {
                id,
                type: notification.type || 'info',
                message: notification.message,
                title: notification.title,
                duration: notification.duration || 5000,
                persistent: notification.persistent || false,
                visible: true,
                progress: 100,
                ...this.getTypeConfig(notification.type)
            };

            this.notifications.push(formattedNotification);

            if (this.soundEnabled) {
                this.playNotificationSound(notification.type);
            }

            if (!formattedNotification.persistent) {
                this.startProgressTimer(id, formattedNotification.duration);
            }

            // Limitar número máximo de notificações
            if (this.notifications.length > config.maxNotifications) {
                this.notifications.shift();
            }
        },

        getTypeConfig(type) {
            const types = {
                success: {
                    icon: 'fas fa-check-circle',
                    classes: 'bg-green-50 dark:bg-green-900/50',
                    iconClasses: 'text-green-400 dark:text-green-300',
                    progressClasses: 'from-green-300 to-green-600'
                },
                error: {
                    icon: 'fas fa-times-circle',
                    classes: 'bg-red-50 dark:bg-red-900/50',
                    iconClasses: 'text-red-400 dark:text-red-300',
                    progressClasses: 'from-red-300 to-red-600'
                },
                warning: {
                    icon: 'fas fa-exclamation-triangle',
                    classes: 'bg-yellow-50 dark:bg-yellow-900/50',
                    iconClasses: 'text-yellow-400 dark:text-yellow-300',
                    progressClasses: 'from-yellow-300 to-yellow-600'
                },
                info: {
                    icon: 'fas fa-info-circle',
                    classes: 'bg-blue-50 dark:bg-blue-900/50',
                    iconClasses: 'text-blue-400 dark:text-blue-300',
                    progressClasses: 'from-blue-300 to-blue-600'
                }
            };

            return types[type] || types.info;
        },

        startProgressTimer(id, duration) {
            const startTime = Date.now();
            const endTime = startTime + duration;

            const updateProgress = () => {
                const now = Date.now();
                const notification = this.notifications.find(n => n.id === id);

                if (!notification) return;

                const remaining = endTime - now;
                const progress = (remaining / duration) * 100;

                if (progress <= 0) {
                    this.removeNotification(id);
                } else {
                    notification.progress = progress;
                    requestAnimationFrame(updateProgress);
                }
            };

            requestAnimationFrame(updateProgress);
        },

        removeNotification(id) {
            const index = this.notifications.findIndex(n => n.id === id);
            if (index > -1) {
                this.notifications[index].visible = false;
                setTimeout(() => {
                    this.notifications = this.notifications.filter(n => n.id !== id);
                }, 300);
            }
        },

        clearNotifications() {
            this.notifications = [];
        },

        generateId() {
            return `notification-${Date.now()}-${Math.random().toString(36).substr(2, 9)}`;
        }
    }));
});