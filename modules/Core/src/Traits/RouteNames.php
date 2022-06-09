<?php

namespace Modules\Core\Traits;


use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Route;
use JetBrains\PhpStorm\Pure;

trait RouteNames
{

    /**
     * Group route names array by prefix
     * Excepts is used if we don't want to get particular route(s) prefix
     *
     * @param array|null $except
     * @return array
     */
    public function getRoutesExcept(array $except = null): array
    {
        $routeNames = $this->getRouteNames();

        $except =  Arr::wrap($except);

        return $this->sortRoutesByName($routeNames, $except);
    }

    /**
     * Get route names array
     *
     * @return array
     */
    public function getRouteNames(): array
    {
        $routes = Route::getRoutes();

        $routeNames = [];
        foreach ($routes as $route) {
            $routeNames[] = $route->getName();
        }

        return $routeNames;
    }

    /**
     * Sort routes by their prefixes
     *
     * @param array $routeNames
     * @param array|null $except
     * @return array
     */
    #[Pure] public function sortRoutesByPrefix(array $routeNames, array $except = null): array
    {
        $routes = [];
        $except =  Arr::wrap($except);

        foreach ($routeNames as $routeName) {
            $routeNameArray = explode('.', $routeName);
            if (! in_array($routeNameArray[0], $except)) {
                $prefix = $routeNameArray[0];
                // unset($routeNameArray[0]);
                $routes[$prefix][] = implode('.', $routeNameArray);
            }
        }
        return $routes;
    }

    /**
     * Sort routes by name
     *
     * @param array $routeNames
     * @param array|null $except
     * @return array
     */
    #[Pure] private function sortRoutesByName(array $routeNames, array $except = null): array
    {
        $routes = [];
        $except =  Arr::wrap($except);

        foreach ($routeNames as $routeName) {
            if (!in_array($routeName, $except)) {
                $routeNameArray = explode('.', $routeName);

                $prefix = $routeNameArray[0];
                // unset($routeNameArray[0]);
                $routes[$prefix][] = implode('.', $routeNameArray);
            }
        }
        return $routes;
    }


}
