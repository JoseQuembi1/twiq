<?php

namespace Twiq\Components;

use Illuminate\Support\Str;
use Illuminate\View\Component;
use Twiq\Icons\IconSet;

class TwiqNotification extends Component
{
    /**
     * Tipos de notificações disponíveis
     * @var array<string>
     */
    public const TYPES = [
        'success',
        'error',
        'warning',
        'info',
    ];

    /**
     * Posições disponíveis para notificações
     * @var array<string>
     */
    public const POSITIONS = [
        'top-right',
        'top-left',
        'bottom-right',
        'bottom-left',
        'top-center',
        'bottom-center',
    ];

    /**
     * ID único da notificação
     * @var string
     */
    public string $id;

    /**
     * Tipo da notificação
     * @var string
     */
    public string $type;

    /**
     * Mensagem da notificação
     * @var string
     */
    public string $message;

    /**
     * Título opcional da notificação
     * @var string|null
     */
    public ?string $title;

    /**
     * Duração da notificação em milissegundos
     * @var int
     */
    public int $duration;

    /**
     * Indica se a notificação é persistente
     * @var bool
     */
    public bool $persistent;

    /**
     * Posição da notificação na tela
     * @var string
     */
    public string $position;

    /**
     * Ícone da notificação
     * @var string|null
     */
    public ?string $icon;

    /**
     * Som de notificação
     * @var bool
     */
    public bool $sound;

    /**
     * Grupo da notificação
     * @var string|null
     */
    public ?string $group;

    /**
     * Classes CSS adicionais
     * @var array<string, array<string>>
     */
    protected array $additionalClasses;

    /**
     * Cria uma nova instância do componente
     */
    public function __construct(
        string $id = null,
        string $type = 'info',
        string $message = '',
        ?string $title = null,
        int $duration = 5000,
        bool $persistent = false,
        string $position = 'top-right',
        ?string $icon = null,
        bool $sound = false,
        ?string $group = null
    ) {
        $this->validateType($type);
        $this->validatePosition($position);
        
        $this->id = $id ?? Str::uuid()->toString();
        $this->type = $type;
        $this->message = $message;
        $this->title = $title;
        $this->duration = $duration;
        $this->persistent = $persistent;
        $this->position = $position;
        $this->icon = $icon ?? $this->getDefaultIcon();
        $this->sound = $sound;
        $this->group = $group;
        
        $this->initializeClasses();
    }

    /**
     * Validar tipo de notificação
     * @throws \InvalidArgumentException
     */
    protected function validateType(string $type): void
    {
        if (!in_array($type, self::TYPES)) {
            throw new \InvalidArgumentException(
                sprintf('Invalid notification type: %s. Allowed types: %s', 
                    $type, 
                    implode(', ', self::TYPES)
                )
            );
        }
    }

    /**
     * Validar posição da notificação
     * @throws \InvalidArgumentException
     */
    protected function validatePosition(string $position): void
    {
        if (!in_array($position, self::POSITIONS)) {
            throw new \InvalidArgumentException(
                sprintf('Invalid notification position: %s. Allowed positions: %s',
                    $position,
                    implode(', ', self::POSITIONS)
                )
            );
        }
    }

    /**
     * Obter ícone padrão baseado no tipo
     */
    protected function getDefaultIcon(): string
    {
        return match ($this->type) {
            'success' => 'check-circle',
            'error' => 'x-circle',
            'warning' => 'exclamation-triangle',
            'info' => 'information-circle',
            default => 'bell'
        };
    }

    /**
     * Inicializar classes CSS
     */
    protected function initializeClasses(): void
    {
        $this->additionalClasses = [
            'base' => $this->getBaseClasses(),
            'icon' => $this->getIconClasses(),
            'content' => $this->getContentClasses(),
            'progress' => $this->getProgressClasses(),
            'close' => $this->getCloseButtonClasses(),
            'container' => $this->getContainerClasses(),
        ];
    }

    /**
     * Obter classes base do componente
     * @return array<string>
     */
    protected function getBaseClasses(): array
    {
        return [
            // Classes base
            'twiq-notification',
            'flex',
            'w-full',
            'max-w-sm',
            'pointer-events-auto',
            'overflow-hidden',
            'rounded-lg',
            'shadow-lg',
            'ring-1',
            'ring-black',
            'ring-opacity-5',
            'transition-all',
            'duration-300',
            'ease-in-out',
            
            // Classes específicas do tipo
            match ($this->type) {
                'success' => 'bg-green-50 dark:bg-green-900/50',
                'error' => 'bg-red-50 dark:bg-red-900/50',
                'warning' => 'bg-yellow-50 dark:bg-yellow-900/50',
                'info' => 'bg-blue-50 dark:bg-blue-900/50',
                default => 'bg-gray-50 dark:bg-gray-900/50'
            },
            
            // Classes responsivas
            'sm:max-w-md',
            'md:max-w-lg',
        ];
    }

    /**
     * Obter classes do ícone
     * @return array<string>
     */
    protected function getIconClasses(): array
    {
        return [
            'flex-shrink-0',
            'w-5',
            'h-5',
            'transition-colors',
            'duration-200',
            match ($this->type) {
                'success' => 'text-green-400 dark:text-green-300',
                'error' => 'text-red-400 dark:text-red-300',
                'warning' => 'text-yellow-400 dark:text-yellow-300',
                'info' => 'text-blue-400 dark:text-blue-300',
                default => 'text-gray-400 dark:text-gray-300'
            }
        ];
    }

    /**
     * Obter classes do conteúdo
     * @return array<string>
     */
    protected function getContentClasses(): array
    {
        return [
            'ml-3',
            'w-0',
            'flex-1',
            'pt-0.5',
        ];
    }

    /**
     * Obter classes da barra de progresso
     * @return array<string>
     */
    protected function getProgressClasses(): array
    {
        return [
            'h-1',
            'bg-gradient-to-r',
            'transition-all',
            'duration-200',
            'ease-linear',
            match ($this->type) {
                'success' => 'from-green-300 to-green-600',
                'error' => 'from-red-300 to-red-600',
                'warning' => 'from-yellow-300 to-yellow-600',
                'info' => 'from-blue-300 to-blue-600',
                default => 'from-gray-300 to-gray-600'
            }
        ];
    }

    /**
     * Obter classes do botão fechar
     * @return array<string>
     */
    protected function getCloseButtonClasses(): array
    {
        return [
            'ml-4',
            'flex-shrink-0',
            'flex',
            'rounded-md',
            'hover:bg-gray-100',
            'dark:hover:bg-gray-800',
            'focus:outline-none',
            'focus:ring-2',
            'focus:ring-offset-2',
            'transition-colors',
            'duration-200',
            match ($this->type) {
                'success' => 'focus:ring-green-500',
                'error' => 'focus:ring-red-500',
                'warning' => 'focus:ring-yellow-500',
                'info' => 'focus:ring-blue-500',
                default => 'focus:ring-gray-500'
            }
        ];
    }

    /**
     * Obter classes do container
     * @return array<string>
     */
    protected function getContainerClasses(): array
    {
        return [
            'fixed',
            'z-50',
            'pointer-events-none',
            match ($this->position) {
                'top-right' => 'top-0 right-0',
                'top-left' => 'top-0 left-0',
                'bottom-right' => 'bottom-0 right-0',
                'bottom-left' => 'bottom-0 left-0',
                'top-center' => 'top-0 left-1/2 transform -translate-x-1/2',
                'bottom-center' => 'bottom-0 left-1/2 transform -translate-x-1/2',
                default => 'top-0 right-0'
            }
        ];
    }

    /**
     * Obter atributos ARIA para acessibilidade
     * @return array<string, string>
     */
    public function getAriaAttributes(): array
    {
        return [
            'role' => 'alert',
            'aria-live' => 'assertive',
            'aria-atomic' => 'true',
            'aria-labelledby' => "notification-{$this->id}-title",
            'aria-describedby' => "notification-{$this->id}-description"
        ];
    }

    /**
     * Obter dados para Alpine.js
     * @return array<string, mixed>
     */
    public function getAlpineData(): array
    {
        return [
            'show' => true,
            'progress' => 100,
            'duration' => $this->duration,
            'persistent' => $this->persistent,
            'sound' => $this->sound,
            'startProgress' => $this->getProgressScript(),
            'dismiss' => $this->getDismissScript(),
            'playSound' => $this->getSoundScript(),
        ];
    }

    /**
     * Obter script de progresso para Alpine.js
     */
    protected function getProgressScript(): string
    {
        return <<<'JS'
        function() {
            if (this.persistent) return;
            
            const start = Date.now();
            const end = start + this.duration;
            
            const updateProgress = () => {
                const now = Date.now();
                const remaining = end - now;
                this.progress = (remaining / this.duration) * 100;
                
                if (this.progress > 0) {
                    requestAnimationFrame(updateProgress);
                } else {
                    this.dismiss();
                }
            };
            
            if (this.sound) {
                this.playSound();
            }
            
            requestAnimationFrame(updateProgress);
        }
        JS;
    }

    /**
     * Obter script de dismiss para Alpine.js
     */
    protected function getDismissScript(): string
    {
        return <<<'JS'
        function() {
            this.show = false;
            setTimeout(() => {
                $dispatch('twiq-dismissed', { 
                    id: '{$this->id}',
                    type: '{$this->type}',
                    group: '{$this->group}'
                });
            }, 300);
        }
        JS;
    }

    /**
     * Obter script de som para Alpine.js
     */
    protected function getSoundScript(): string
    {
        return <<<'JS'
        function() {
            if (!this.sound) return;
            
            const audio = new (window.AudioContext || window.webkitAudioContext)();
            const oscillator = audio.createOscillator();
            const gainNode = audio.createGain();
            
            oscillator.connect(gainNode);
            gainNode.connect(audio.destination);
            
            const sounds = {
                success: { frequency: 880, duration: 0.1 },
                error: { frequency: 220, duration: 0.2 },
                warning: { frequency: 440, duration: 0.15 },
                info: { frequency: 660, duration: 0.1 }
            };
            
            const sound = sounds['{$this->type}'] || sounds.info;
            
            oscillator.frequency.value = sound.frequency;
            gainNode.gain.value = 0.1;
            
            oscillator.start();
            oscillator.stop(audio.currentTime + sound.duration);
        }
        JS;
    }

    /**
     * Renderizar o componente
     */
    public function render()
    {
        return view('twiq::components.notification', [
            'classes' => $this->additionalClasses,
            'aria' => $this->getAriaAttributes(),
            'alpine' => $this->getAlpineData(),
            'icon' => IconSet::render($this->icon, [
                'class' => implode(' ', $this->getIconClasses()),
                'aria-hidden' => 'true'
            ])
        ]);
    }
}