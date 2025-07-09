<?php

namespace Twiq\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class TwiqInstallCommand extends Command
{
    protected $signature = 'twiq:install {--force : Overwrite existing files}';
    protected $description = 'Install Twiq notification system';

    public function handle(): int
    {
        $this->info('Installing Twiq Notification System...');

        // Publicar configuração
        $this->call('vendor:publish', [
            '--tag' => 'twiq-config',
            '--force' => $this->option('force'),
        ]);

        // Publicar assets JavaScript
        $this->call('vendor:publish', [
            '--tag' => 'twiq-assets',
            '--force' => $this->option('force'),
        ]);

        // Verificar se o app.js existe e sugerir integração
        $appJsPath = resource_path('js/app.js');
        if (File::exists($appJsPath)) {
            $appJsContent = File::get($appJsPath);
            
            if (!str_contains($appJsContent, 'twiq.js')) {
                $this->warn('Don\'t forget to add the following line to your resources/js/app.js:');
                $this->line('import \'./vendor/twiq/twiq.js\';');
            }
        }

        // Verificar TailwindCSS
        $tailwindConfigPath = base_path('tailwind.config.js');
        if (File::exists($tailwindConfigPath)) {
            $this->info('TailwindCSS detected. Make sure to include the Twiq views in your content array:');
            $this->line('\'./vendor/twiq/resources/views/**/*.blade.php\'');
        }

        $this->info('Twiq has been installed successfully!');
        $this->line('Add <x-twiq /> to your layout file to start using notifications.');

        return Command::SUCCESS;
    }
}