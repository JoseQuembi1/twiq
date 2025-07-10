<?php

namespace Twiq\Icons;

class IconSet
{
    /**
     * Coleção de ícones SVG padrão
     */
    protected static array $icons = [
        'success' => '<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
        </svg>',
        
        'error' => '<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m9-.75a9 9 0 11-18 0 9 9 0 0118 0zm-9 3.75h.008v.008H12v-.008z" />
        </svg>',
        
        'warning' => '<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126zM12 15.75h.007v.008H12v-.008z" />
        </svg>',
        
        'info' => '<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" d="M11.25 11.25l.041-.02a.75.75 0 011.063.852l-.708 2.836a.75.75 0 001.063.853l.041-.021M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-9-3.75h.008v.008H12V8.25z" />
        </svg>',
        
        'close' => '<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
        </svg>',

        'loading' => '<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="animate-spin">
            <path stroke-linecap="round" stroke-linejoin="round" d="M16.023 9.348h4.992v-.001M2.985 19.644v-4.992m0 0h4.992m-4.993 0l3.181 3.183a8.25 8.25 0 0013.803-3.7M15.269 1.771l-3.182 3.182c-1.149 1.149-1.785 2.706-1.785 4.329 0 1.623.636 3.18 1.785 4.329l3.182 3.182m0-14.485v4.992" />
        </svg>'
    ];

    /**
     * Ícones personalizados registrados
     */
    protected static array $customIcons = [];

    /**
     * Registrar um novo ícone personalizado
     */
    public static function register(string $name, string $svg): void
    {
        static::$customIcons[$name] = $svg;
    }

    /**
     * Obter um ícone por nome
     */
    public static function get(string $name): ?string
    {
        return static::$customIcons[$name] ?? static::$icons[$name] ?? null;
    }

    /**
     * Verificar se um ícone existe
     */
    public static function has(string $name): bool
    {
        return isset(static::$customIcons[$name]) || isset(static::$icons[$name]);
    }

    /**
     * Renderizar um ícone com atributos personalizados
     */
    public static function render(string $name, array $attributes = []): string
    {
        if (!static::has($name)) {
            return '';
        }

        $svg = static::get($name);

        // Adicionar atributos personalizados
        if (!empty($attributes)) {
            $attributesString = collect($attributes)
                ->map(fn ($value, $key) => sprintf('%s="%s"', $key, $value))
                ->implode(' ');

            $svg = preg_replace('/<svg/', "<svg {$attributesString}", $svg);
        }

        return $svg;
    }
}