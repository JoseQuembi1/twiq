# Twiq - Elegant Laravel Notifications

[![Latest Version on Packagist](https://img.shields.io/packagist/v/twiq/laravel-notifications.svg?style=flat-square)](https://packagist.org/packages/twiq/laravel-notifications)
[![Total Downloads](https://img.shields.io/packagist/dt/twiq/laravel-notifications.svg?style=flat-square)](https://packagist.org/packages/twiq/laravel-notifications)
[![License](https://img.shields.io/packagist/l/twiq/laravel-notifications.svg?style=flat-square)](https://packagist.org/packages/twiq/laravel-notifications)

**Twiq** .

## ✨ Características

- 🎨 **Design Moderno**: Interface limpa e elegante com TailwindCSS
- 🔄 **Reatividade**: Integração perfeita com Livewire 3 e Alpine.js
- 🎯 **Múltiplos Tipos**: Success, Error, Warning, Info
- ⏱️ **Auto-dismiss**: Notificações com temporizador automático
- 📌 **Persistentes**: Notificações que ficam até serem fechadas manualmente
- 🔄 **Prevenção de Duplicatas**: Sistema inteligente anti-spam
- 📱 **Responsivo**: Funciona perfeitamente em mobile e desktop
- 🌙 **Modo Escuro**: Suporte automático baseado no sistema
- 🔊 **Som**: Feedback sonoro opcional para notificações
- 🌐 **Multilíngue**: Suporte para múltiplos idiomas
- 📦 **Agrupamento**: Notificações similares podem ser agrupadas
- 🎭 **Animações**: Transições suaves e profissionais

## 📋 Requisitos

- PHP 8.1+
- Laravel 10.0+ ou 11.0+
- Livewire 3.0+
- TailwindCSS 3.x
- Alpine.js 3.x

## 🚀 Instalação

```bash
composer require twiq/laravel-notifications
```

### Publicar Arquivos

```bash
# Publicar configuração
php artisan vendor:publish --tag=twiq-config

# Publicar views (opcional)
php artisan vendor:publish --tag=twiq-views

# Publicar assets JavaScript
php artisan vendor:publish --tag=twiq-assets

# Publicar traduções (opcional)
php artisan vendor:publish --tag=twiq-translations
```

### Configurar Assets

Adicione o JavaScript do Twiq ao seu `resources/js/app.js`:

```javascript
import './vendor/twiq/twiq.js';
```

## 📖 Uso Básico

### 1. Adicionar o Componente

Adicione o componente Twiq ao seu layout principal:

```blade
<!DOCTYPE html>
<html>
<head>
    <!-- ... -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body>
    <!-- Seu conteúdo -->
    
    <!-- Componente Twiq -->
    <x-twiq />
    
    <!-- Ou usando Livewire -->
    <livewire:twiq-container />
</body>
</html>
```

### 2. Disparar Notificações

#### Em Componentes Livewire

```php
<?php

namespace App\Http\Livewire;

use Livewire\Component;
use Twiq\Traits\HasTwiqNotifications;

class UserForm extends Component
{
    use HasTwiqNotifications;

    public function save()
    {
        // Lógica de salvamento...
        
        $this->notifySuccess('Usuário salvo com sucesso!');
        
        // Ou usando dispatch direto
        $this->dispatch('twiq', [
            'type' => 'success',
            'message' => 'Usuário salvo com sucesso!'
        ]);
    }
    
    public function delete()
    {
        // Lógica de exclusão...
        
        $this->notifyError('Erro ao excluir usuário');
    }
}
```

#### Em Controllers

```php
<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Twiq\Facades\Twiq;

class UserController extends Controller
{
    public function store(Request $request)
    {
        // Lógica de salvamento...
        
        // Usando Facade
        Twiq::success('Usuário criado com sucesso!');
        
        return redirect()->back();
    }
}
```

#### JavaScript/Alpine.js

```javascript
// Usando helper global
twiq.success('Operação realizada!');
twiq.error('Algo deu errado!');
twiq.warning('Atenção!');
twiq.info('Informação importante');

// Ou disparando evento customizado
window.dispatchEvent(new CustomEvent('twiq', {
    detail: {
        type: 'success',
        message: 'Notificação personalizada!'
    }
}));
```

## 🎨 Tipos de Notificação

### Tipos Disponíveis

```php
// Sucesso (verde)
$this->notifySuccess('Operação realizada com sucesso!');

// Erro (vermelho)
$this->notifyError('Ocorreu um erro!');

// Aviso (amarelo)
$this->notifyWarning('Atenção necessária!');

// Informação (azul)
$this->notifyInfo('Informação importante!');
```

### Notificações Personalizadas

```php
$this->dispatch('twiq', [
    'type' => 'success',
    'message' => 'Mensagem principal',
    'title' => 'Título opcional',
    'persistent' => true, // Não desaparece automaticamente
    'duration' => 8000, // 8 segundos
]);
```

## ⚙️ Configuração

O arquivo `config/twiq.php` permite personalizar completamente o comportamento:

```php
return [
    // Posição das notificações
    'position' => 'top-right', // top-left, bottom-right, etc.
    
    // Duração padrão (ms)
    'duration' => 5000,
    
    // Máximo de notificações simultâneas
    'max_notifications' => 5,
    
    // Habilitar som
    'sound' => false,
    
    // Modo escuro automático
    'dark_mode' => 'auto',
    
    // Configuração por tipo
    'types' => [
        'success' => [
            'icon' => 'check-circle',
            'color' => 'green',
            'duration' => 4000,
        ],
        // ...
    ],
    
    // Agrupamento de notificações
    'grouping' => [
        'enabled' => true,
        'timeout' => 2000,
    ],
    
    // Prevenir duplicatas
    'prevent_duplicates' => true,
];
```

## 🎯 Funcionalidades Avançadas

### Notificações Persistentes

```php
// Notificação que só sai manualmente
$this->notifyPersistent('error', 'Erro crítico do sistema');

// Ou usando dispatch
$this->dispatch('twiq', [
    'type' => 'error',
    'message' => 'Erro crítico',
    'persistent' => true
]);
```

### Posicionamento Personalizado

```blade
<!-- Notificações no topo à esquerda -->
<x-twiq position="top-left" />

<!-- Notificações na parte inferior central -->
<x-twiq position="bottom-center" />
```

### Agrupamento de Notificações

Quando habilitado, notificações similares são agrupadas automaticamente:

```php
// Estas notificações serão agrupadas se disparadas rapidamente
$this->notifyError('Erro de validação');
$this->notifyError('Erro de validação');
$this->notifyError('Erro de validação');
// Resultado: "Erro de validação (3)"
```

### Integração com Formulários

```php
public function submit()
{
    $this->validate([
        'name' => 'required',
        'email' => 'required|email',
    ]);
    
    try {
        // Lógica de salvamento...
        $this->notifySuccess('Dados salvos com sucesso!');
    } catch (\Exception $e) {
        $this->notifyError('Erro ao salvar: ' . $e->getMessage());
    }
}
```

## 🌐 Internacionalização

### Traduções Disponíveis

- Português (pt)
- Inglês (en)

### Personalizando Traduções

```php
// resources/lang/pt/twiq.php
return [
    'close' => 'Fechar',
    'success' => 'Sucesso',
    'error' => 'Erro',
    'warning' => 'Aviso',
    'info' => 'Informação',
    'default_messages' => [
        'success' => 'Operação realizada com sucesso!',
        'error' => 'Ocorreu um erro. Tente novamente.',
        // ...
    ],
];
```

## 🎵 Som

Para habilitar feedback sonoro:

```php
// config/twiq.php
'sound' => true,
```

Os sons são gerados automaticamente usando Web Audio API e são diferentes para cada tipo de notificação.

## 🧪 Testes

```bash
composer test
```

### Exemplo de Teste

```php
public function test_can_add_notification()
{
    Livewire::test(TwiqContainer::class)
        ->call('addNotification', [
            'type' => 'success',
            'message' => 'Test notification'
        ])
        ->assertSee('Test notification');
}
```

## 🎨 Personalização de Estilos

O Twiq usa classes TailwindCSS padrão. Para personalizar:

```css
/* resources/css/app.css */
.twiq-notification {
    @apply backdrop-blur-sm bg-white/90;
}

.twiq-notification.success {
    @apply border-l-4 border-green-500;
}

.dark .twiq-notification {
    @apply bg-gray-800/90;
}
```

## 📱 Responsividade

O Twiq é totalmente responsivo:

- **Desktop**: Notificações posicionadas conforme configuração
- **Mobile**: Notificações ocupam toda a largura com margens laterais
- **Tablet**: Comportamento híbrido inteligente

## 🔧 Integração com Outros Pacotes

### Laravel Flash Messages

```php
// Converter flash messages para Twiq
if (session()->has('success')) {
    $this->notifySuccess(session('success'));
}
```

### Validation Errors

```php
// Mostrar erros de validação
if ($this->getErrorBag()->any()) {
    foreach ($this->getErrorBag()->all() as $error) {
        $this->notifyError($error);
    }
}
```

## 🤝 Contribuindo

Contribuições são bem-vindas! Por favor:

1. Fork o projeto
2. Crie uma branch para sua feature
3. Commit suas mudanças
4. Push para a branch
5. Abra um Pull Request

## 📄 Licença

Este pacote é open-source licenciado sob [MIT license](LICENSE.md).

## 🙏 Créditos

- **Twiq Team**
- **Laravel** - Framework incrível
- **Livewire** - Reatividade moderna
- **TailwindCSS** - Styling utilities
- **Alpine.js** - JavaScript reativo

## 📞 Suporte

Para suporte, abra uma [issue](https://github.com/josequembi1/twiq/laravel-notifications/issues) ou entre em contato:

- Email: josequembi15@gmail.com

---

**Feito com ❤️ pela equipe Twiq**
