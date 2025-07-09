<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Posição das Notificações
    |--------------------------------------------------------------------------
    |
    | Define onde as notificações aparecerão na tela.
    | Opções: top-left, top-center, top-right, bottom-left, bottom-center, bottom-right
    |
    */
    'position' => 'top-right',

    /*
    |--------------------------------------------------------------------------
    | Duração Padrão
    |--------------------------------------------------------------------------
    |
    | Tempo em milissegundos que as notificações ficam visíveis antes de
    | desaparecerem automaticamente. Use 0 para tornar persistente.
    |
    */
    'duration' => 5000,

    /*
    |--------------------------------------------------------------------------
    | Máximo de Notificações
    |--------------------------------------------------------------------------
    |
    | Número máximo de notificações que podem ser exibidas simultaneamente.
    |
    */
    'max_notifications' => 5,

    /*
    |--------------------------------------------------------------------------
    | Som
    |--------------------------------------------------------------------------
    |
    | Habilita/desabilita o som das notificações.
    |
    */
    'sound' => false,

    /*
    |--------------------------------------------------------------------------
    | Modo Escuro
    |--------------------------------------------------------------------------
    |
    | Configuração do modo escuro. Opções: auto, light, dark
    |
    */
    'dark_mode' => 'auto',

    /*
    |--------------------------------------------------------------------------
    | Prevenção de Duplicatas
    |--------------------------------------------------------------------------
    |
    | Evita que notificações idênticas sejam exibidas múltiplas vezes.
    |
    */
    'prevent_duplicates' => true,

    /*
    |--------------------------------------------------------------------------
    | Agrupamento
    |--------------------------------------------------------------------------
    |
    | Configurações para agrupamento de notificações similares.
    |
    */
    'grouping' => [
        'enabled' => true,
        'timeout' => 2000,
    ],

    /*
    |--------------------------------------------------------------------------
    | Configuração por Tipo
    |--------------------------------------------------------------------------
    |
    | Configurações específicas para cada tipo de notificação.
    |
    */
    'types' => [
        'success' => [
            'icon' => 'check-circle',
            'color' => 'green',
            'duration' => 4000,
        ],
        'error' => [
            'icon' => 'x-circle',
            'color' => 'red',
            'duration' => 6000,
        ],
        'warning' => [
            'icon' => 'exclamation-triangle',
            'color' => 'yellow',
            'duration' => 5000,
        ],
        'info' => [
            'icon' => 'information-circle',
            'color' => 'blue',
            'duration' => 4000,
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Animações
    |--------------------------------------------------------------------------
    |
    | Configurações de animações e transições.
    |
    */
    'animations' => [
        'enter' => 'fadeInDown',
        'exit' => 'fadeOutUp',
        'duration' => 300,
    ],

    /*
    |--------------------------------------------------------------------------
    | Responsividade
    |--------------------------------------------------------------------------
    |
    | Configurações específicas para diferentes tamanhos de tela.
    |
    */
    'responsive' => [
        'mobile' => [
            'position' => 'top-center',
            'width' => '100%',
            'margin' => '1rem',
        ],
        'tablet' => [
            'position' => 'top-right',
            'width' => 'auto',
            'margin' => '1rem',
        ],
        'desktop' => [
            'position' => 'top-right',
            'width' => 'auto',
            'margin' => '1rem',
        ],
    ],
];