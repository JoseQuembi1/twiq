<div
    x-data='@json($alpine)'
    x-show="show"
    x-init="startProgress()"
    x-transition:enter="transform ease-out duration-300 transition"
    x-transition:enter-start="translate-y-2 opacity-0 sm:translate-y-0 sm:translate-x-2"
    x-transition:enter-end="translate-y-0 opacity-100 sm:translate-x-0"
    x-transition:leave="transition ease-in duration-100"
    x-transition:leave-start="opacity-100"
    x-transition:leave-end="opacity-0"
    {{ $attributes->merge(['class' => implode(' ', $classes['base'])]) }}
    @foreach($aria as $key => $value)
        {{ $key }}="{{ $value }}"
    @endforeach
>
    <div class="p-4">
        <div class="flex items-start">
            {{-- Ícone --}}
            @if($icon)
                <div class="{{ implode(' ', $classes['icon']) }}">
                    <x-heroicon-s-{{ $icon }} />
                </div>
            @endif

            {{-- Conteúdo --}}
            <div class="{{ implode(' ', $classes['content']) }}">
                @if($title)
                    <p 
                        id="notification-{{ $id }}-title"
                        class="text-sm font-medium text-gray-900 dark:text-white"
                    >
                        {{ $title }}
                    </p>
                @endif
                
                <p 
                    id="notification-{{ $id }}-description"
                    class="mt-1 text-sm text-gray-500 dark:text-gray-400"
                >
                    {{ $message }}
                </p>
            </div>

            {{-- Botão Fechar --}}
            <div class="{{ implode(' ', $classes['close']) }}">
                <button
                    type="button"
                    @click="dismiss()"
                    class="rounded-md p-1.5 inline-flex text-gray-400 hover:text-gray-500 focus:outline-none focus:ring-2 focus:ring-offset-2"
                >
                    <span class="sr-only">{{ __('twiq::notifications.close') }}</span>
                    <x-heroicon-s-x-mark class="h-5 w-5" />
                </button>
            </div>
        </div>
    </div>

    {{-- Barra de Progresso --}}
    @if(!$persistent)
        <div
            x-show="progress > 0"
            class="{{ implode(' ', $classes['progress']) }}"
            :style="{ width: progress + '%' }"
        ></div>
    @endif
</div>