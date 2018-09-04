<?php

namespace EliPett\ProgressCommand;

use Illuminate\Support\ServiceProvider;

class ProgressCommandServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        $this->publishAssets();
    }

    private function publishAssets()
    {
        $this->publishes([
            __DIR__ . '/../config/progresscommand.php' => config_path('progresscommand.php'),
        ], 'config');
    }
}
