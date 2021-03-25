<?php

namespace KielD01\Middlewares;

use KielD01\Adapters\RPCAdapter;
use Closure;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

/**
 * Class AdapterMiddleware
 * @package App\Packages\TechGenerationAdapters\Middlewares
 */
class AdapterMiddleware
{
    private array $routes = [];

    /**
     * @param Request $request
     * @param Closure $next
     * @return JsonResponse|mixed|Closure
     */
    public function handle(Request $request, Closure $next)
    {
        $this->loadAdaptedRoutes();
        $baseRoute = $request->route();

        $route = $baseRoute->getName() ?? $baseRoute->uri();

        if ($route) {
            [$adapter, $method, $active] = $this->getAdaptedRoute(
                $route, \mb_strtolower($request->method())
            );

            if ($active) {
                /** @var RPCAdapter $adapter */
                $adapter = resolve($adapter);
                $adapter->setRequest($request);

                $adapter->{$method}();

                $adapterResponse = $adapter->getResponse();

                return response()
                    ->json(
                        $adapterResponse->getData(),
                        $adapterResponse->getCode(),
                        [],
                        JSON_PRETTY_PRINT
                    );
            }
        }

        return $next($request);
    }

    /**
     * Loads routes for Adapters
     */
    private function loadAdaptedRoutes(): void
    {
        $this->routes = config('adapter_routes', []);

        Log::info('Adapter Routes has been loaded :');
        Log::info($this->routes);
    }

    /**
     * @param string $route
     * @param string $method
     * @return array|false|null
     */
    private function getAdaptedRoute(string $route, string $method)
    {
        $anyRoutePattern = \sprintf('%s.any', $route);
        $specificRoutePattern = \sprintf('%s.%s', $route, $method);

        $anyRoute = array_key_exists($anyRoutePattern, $this->routes);
        $specificRoute = array_key_exists($specificRoutePattern, $this->routes);

        if ($specificRoute) {
            return $this->routes[$specificRoutePattern];
        }

        return $anyRoute ?
            $this->routes[$anyRoutePattern] :
            false;
    }
}
