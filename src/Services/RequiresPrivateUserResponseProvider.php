<?php

namespace App\Services;

use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Routing\RouterInterface;

class RequiresPrivateUserResponseProvider
{
    /**
     * @var array
     */
    private $map = [];

    /**
     * @var RouterInterface
     */
    private $router;

    public function __construct(CachedDataProvider $dataProvider, RouterInterface $router)
    {
        $this->map = $dataProvider->getData();
        $this->router = $router;
    }

    public function getResponse(string $requestMethod, string $requestPath)
    {
        foreach ($this->map as $urlPathPattern => $requestMethodRedirectActionMap) {
            $regex = '/'. str_replace('/', '\\/', $urlPathPattern) .'/';

            if ($requestMethodRedirectActionMap[$requestMethod] && preg_match($regex, $requestPath)) {
                $redirectActions = $requestMethodRedirectActionMap[$requestMethod];

                foreach ($redirectActions as $pattern => $routeName) {
                    $regex = '/'. str_replace('/', '\\/', $pattern) .'/';

                    if (preg_match($regex, $requestPath)) {
                        return new RedirectResponse($this->router->generate(
                            'view_user_sign_in',
                            [
                                'redirect' => base64_encode(json_encode(['route' => $routeName]))
                            ]
                        ));
                    }
                }
            }
        }

        return null;
    }
}
