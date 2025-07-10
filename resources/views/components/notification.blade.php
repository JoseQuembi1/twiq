<div
    x-data='@json($alpine)'
    x-show="show"
    x-init="startProgress()"
    x-transition:enter="transform ease-out duration-500 transition-gpu"
    x-transition:enter-start="translate-y-1 scale-95 opacity-0"
    x-transition:enter-end="translate-y-0 scale-100 opacity-100"
    x-transition:leave="transform ease-in duration-300 transition-gpu"
    x-transition:leave-start="translate-y-0 scale-100 opacity-100"
    x-transition:leave-end="translate-y-1 scale-95 opacity-0"
    {{ $attributes->merge(['class' => implode(' ', $classes['base'])]) }}
    @foreach($aria as $key => $value)
        {{ $key }}="{{ $value }}"
    @endforeach
>
    {{-- Fundo com Efeito Glassmorphism --}}
    <div class="relative overflow-hidden rounded-xl backdrop-blur-lg bg-white/90 dark:bg-gray-900/90 shadow-xl ring-1 ring-black/5 dark:ring-white/5">
        {{-- Efeito de Borda Gradiente --}}
        <div class="absolute inset-0 rounded-xl" 
             :class="{
                'bg-gradient-to-r from-green-500/20 to-emerald-500/20 dark:from-green-500/10 dark:to-emerald-500/10': type === 'success',
                'bg-gradient-to-r from-red-500/20 to-rose-500/20 dark:from-red-500/10 dark:to-rose-500/10': type === 'error',
                'bg-gradient-to-r from-yellow-500/20 to-amber-500/20 dark:from-yellow-500/10 dark:to-amber-500/10': type === 'warning',
                'bg-gradient-to-r from-blue-500/20 to-indigo-500/20 dark:from-blue-500/10 dark:to-indigo-500/10': type === 'info'
             }">
        </div>

        {{-- Conteúdo Principal --}}
        <div class="relative p-4">
            <div class="flex items-start gap-3">
                {{-- Ícone com Animação --}}
                @if($icon)
                    <div class="flex-shrink-0 transition-transform duration-300 ease-out hover:scale-110">
                        <div class="relative">
                            {{-- Círculo de Fundo com Blur --}}
                            <div class="absolute inset-0 blur-sm opacity-50"
                                 :class="{
                                    'bg-green-500/30': type === 'success',
                                    'bg-red-500/30': type === 'error',
                                    'bg-yellow-500/30': type === 'warning',
                                    'bg-blue-500/30': type === 'info'
                                 }">
                            </div>
                            {{-- Ícone --}}
                            <div class="relative">
                                {!! $icon !!}
                            </div>
                        </div>
                    </div>
                @endif

                {{-- Conteúdo de Texto --}}
                <div class="flex-1 min-w-0">
                    @if($title)
                        <h3 
                            id="notification-{{ $id }}-title"
                            class="text-sm font-semibold tracking-wide"
                            :class="{
                                'text-green-900 dark:text-green-100': type === 'success',
                                'text-red-900 dark:text-red-100': type === 'error',
                                'text-yellow-900 dark:text-yellow-100': type === 'warning',
                                'text-blue-900 dark:text-blue-100': type === 'info'
                            }"
                        >
                            {{ $title }}
                        </h3>
                    @endif
                    
                    <p 
                        id="notification-{{ $id }}-description"
                        class="mt-1 text-sm leading-relaxed text-gray-600 dark:text-gray-300"
                    >
                        {{ $message }}
                    </p>
                </div>

                {{-- Botão Fechar com Efeito Hover --}}
                <button
                    type="button"
                    @click="dismiss()"
                    class="flex-shrink-0 group relative p-1.5 rounded-full hover:bg-gray-100 dark:hover:bg-gray-800 transition-all duration-300 ease-out"
                >
                    <span class="sr-only">{{ __('twiq::notifications.close') }}</span>
                    {{-- Efeito de Ripple no Hover --}}
                    <div class="absolute inset-0 rounded-full opacity-0 group-hover:opacity-100 transition-opacity duration-300"
                         :class="{
                            'bg-green-500/10': type === 'success',
                            'bg-red-500/10': type === 'error',
                            'bg-yellow-500/10': type === 'warning',
                            'bg-blue-500/10': type === 'info'
                         }">
                    </div>
                    {{-- Ícone de Fechar --}}
                    <svg class="w-4 h-4 transform transition-transform duration-300 group-hover:rotate-90 group-hover:scale-110"
                         :class="{
                            'text-green-600 dark:text-green-400': type === 'success',
                            'text-red-600 dark:text-red-400': type === 'error',
                            'text-yellow-600 dark:text-yellow-400': type === 'warning',
                            'text-blue-600 dark:text-blue-400': type === 'info'
                         }"
                         xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd" />
                    </svg>
                </button>
            </div>
        </div>

        {{-- Barra de Progresso Moderna --}}
        @if(!$persistent)
            <div class="relative h-0.5 overflow-hidden">
                <div
                    x-show="progress > 0"
                    class="absolute bottom-0 left-0 right-0 transition-all duration-300 ease-linear"
                    :class="{
                        'bg-gradient-to-r from-green-300 via-green-400 to-emerald-500': type === 'success',
                        'bg-gradient-to-r from-red-300 via-red-400 to-rose-500': type === 'error',
                        'bg-gradient-to-r from-yellow-300 via-yellow-400 to-amber-500': type === 'warning',
                        'bg-gradient-to-r from-blue-300 via-blue-400 to-indigo-500': type === 'info'
                    }"
                    :style="{ width: progress + '%' }"
                >
                    {{-- Efeito de Brilho --}}
                    <div class="absolute inset-0 blur-sm"
                         :class="{
                            'bg-green-400/50': type === 'success',
                            'bg-red-400/50': type === 'error',
                            'bg-yellow-400/50': type === 'warning',
                            'bg-blue-400/50': type === 'info'
                         }">
                    </div>
                </div>
            </div>
        @endif
    </div>
</div>