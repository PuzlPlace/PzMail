<?php

declare(strict_types=1);

namespace Puzl\PzMail\Laravel;

use Illuminate\Support\ServiceProvider;

final class PzMailServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->mergeConfigFrom(__DIR__.'/../../config/pzmail.php', 'pzmail');
    }

    public function boot(): void
    {
        $this->publishes([
            __DIR__.'/../../config/pzmail.php' => config_path('pzmail.php'),
        ], 'pzmail-config');
    }
}
