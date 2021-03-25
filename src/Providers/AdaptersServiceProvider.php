<?php

namespace KielD01\Providers;

use Illuminate\Foundation\Http\Kernel;
use Illuminate\Support\ServiceProvider;
use KielD01\Console\Commands\MakeAdapterCommand;
use KielD01\Middlewares\AdapterMiddleware;

/**
 * Class MarketAdapterServiceProvider
 * @package App\Packages\TechGenerationAdapters\Providers
 */
class AdaptersServiceProvider extends ServiceProvider
{

    /**
     * Adapter Package Commands list
     *
     * @var string[]
     */
    private array $commands = [
        MakeAdapterCommand::class
    ];

    public function boot(Kernel $kernel): void
    {
        $this->setPublishable();
        $this->registerCommands();
        $this->registerRoutes();
        $this->registerMiddlewareToGroups($kernel);
    }

    /**
     * Register middleware to a specific group/groups
     *
     * @param Kernel $kernel
     */
    private function registerMiddlewareToGroups(Kernel $kernel): void
    {
        $groups = config(
            'adapters.middleware_groups',
            config('adapters.default_middleware_groups', [])
        );

        $middlewareGroups = collect(is_array($groups) ? $groups : [$groups]);

        if ($middlewareGroups->count()) {
            $middlewareGroups->each(static function ($group) use ($kernel) {
                $kernel->prependMiddlewareToGroup($group, AdapterMiddleware::class);
            });
        }
    }

    /**
     * Registering commands list
     */
    private function registerCommands(): void
    {
        $this->commands($this->commands);
    }


    private function registerRoutes()
    {
        $this
            ->loadRoutesFrom(
                __DIR__ . DIRECTORY_SEPARATOR .
                '..' . DIRECTORY_SEPARATOR .
                'routes' . DIRECTORY_SEPARATOR .
                'test.routes.php'
            );
    }

    private function setPublishable(): void
    {
        $baseDir = __DIR__ . DIRECTORY_SEPARATOR . '..';
        $configs = \sprintf('%s%s%s', $baseDir, DIRECTORY_SEPARATOR, 'config');
        $adapterConfig = \sprintf('%s%s%s', $configs, DIRECTORY_SEPARATOR, 'adapters.php');
        $adapterRoutes = \sprintf('%s%s%s', $configs, DIRECTORY_SEPARATOR, 'adapter_routes.php');

        $this->publishes([
            $adapterConfig => config_path('adapters.php'),
            $adapterRoutes => config_path('adapter_routes.php')
        ]);
    }
}
