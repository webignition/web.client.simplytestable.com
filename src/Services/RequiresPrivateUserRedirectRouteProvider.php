<?php

namespace App\Services;

class RequiresPrivateUserRedirectRouteProvider
{
    /**
     * @var array
     */
    private $map = [];

    /**
     * @param CachedDataProvider $redirectRouteMapProvider
     */
    public function __construct(CachedDataProvider $redirectRouteMapProvider)
    {
        $this->map = $redirectRouteMapProvider->getData();
    }

    public function getRouteForUrlPathPattern(string $urlPathPattern, string $requestMethod, string $requestPath)
    {
        if (!isset($this->map[$urlPathPattern])) {
            return null;
        }

        if (!isset($this->map[$urlPathPattern][$requestMethod])) {
            return null;
        }

        $patterns = $this->map[$urlPathPattern][$requestMethod];

        foreach ($patterns as $pattern => $routeName) {
            $regex = '/'. str_replace('/', '\\/', $pattern) .'/';

            if (preg_match($regex, $requestPath)) {
                return $routeName;
            }
        }

        return null;
    }
}
