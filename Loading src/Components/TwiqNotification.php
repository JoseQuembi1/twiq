<?php

namespace Twiq\Components;

use Illuminate\View\Component;

class TwiqNotification extends Component
{
    /**
     * Tipos de notificações disponíveis
     */
    public const TYPES = [
        'success',
        'error',
        'warning',
        'info'
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
     * Ícone da notificação
     * @var string|null
     */
    public ?string $icon;

    /**
     * Classes CSS adicionais
     * @var array
     */
    protected array $additionalClasses;

    /**
     * Construtor do componente
     */
    public function __construct(
        string $id,
        string $type = 'info',
        string $message = '',
        ?string $title = null,
        int $duration = 5000,
        bool $persistent = false,
        ?string $icon = null
    ) {
        $this->validateType($type);
        
        $this->id = $id;
        $this->type = $type;
        $this->message = $message;
        $this->title = $title;
        $this->duration = $duration;
        $this->persistent = $persistent;
        $this->icon = $icon ?? $this->getDefaultIcon();
        
        $this->initializeClasses();
    }

    /**
     * Validar tipo de notificação
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
        ];
    }

    /**
     * Obter classes base do componente
     */
    protected function getBaseClasses(): array
    {
        return [
            // Classes base
            'notification',
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
     */
    protected function getIconClasses(): array
    {
        return [
            // Classes base do ícone
            'flex-shrink-0',
            'w-5',
            'h-5',
            
            // Classes de cor baseadas no tipo
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
     */
    protected function getProgressClasses(): array
    {
        return [
            'h-1',
            'bg-gradient-to-r',
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
     * Obter atributos ARIA para acessibilidade
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
     */
    public function getAlpineData(): array
    {
        return [
            'show' => true,
            'progress' => 100,
            'duration' => $this->duration,
            'persistent' => $this->persistent,
            'startProgress' => 'function() {
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
                
                requestAnimationFrame(updateProgress);
            }',
            'dismiss' => 'function() {
                this.show = false;
                setTimeout(() => {
                    $dispatch("twiq-dismissed", { id: "' . $this->id . '" });
                }, 300);
            }'
        ];
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
        ]);
    }
}