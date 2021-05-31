<?php

namespace App\Services;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\RouterInterface;

class RequiresValidTestOwnerResponseProvider
{
    /**
     * @var array
     */
    private $map = [];

    /**
     * @var RouterInterface
     */
    private $router;

    /**
     * @var UserManager
     */
    private $userManager;

    public function __construct(CachedDataProvider $dataProvider, RouterInterface $router, UserManager $userManager)
    {
        $this->map = $dataProvider->getData();
        $this->router = $router;
        $this->userManager = $userManager;
    }

    public function getResponse(Request $request)
    {
        $requestPath = $request->getPathInfo();

        foreach ($this->map as $urlPathPattern => $responseProperties) {
            $regex = '/'. str_replace('/', '\\/', $urlPathPattern) .'/';

            if (preg_match($regex, $requestPath)) {
                $type = $responseProperties['type'];

                if ('default' === $type) {
                    if ($this->userManager->isLoggedIn()) {
                        return new RedirectResponse($this->router->generate(
                            'view_test_unauthorised',
                            [
                                'website' => $request->attributes->get('website'),
                                'test_id' => $request->attributes->get('test_id')
                            ]
                        ));
                    }

                    $redirectParameters = json_encode([
                        'route' => 'view_test_progress',
                        'parameters' => [
                            'website' => $request->attributes->get('website'),
                            'test_id' => $request->attributes->get('test_id')
                        ]
                    ]);

                    return new RedirectResponse($this->router->generate(
                        'sign_in_render',
                        [
                            'redirect' => base64_encode($redirectParameters)
                        ]
                    ));
                }

                if ('Response' === $type) {
                    $statusCode = isset($responseProperties['statusCode'])
                        ? (int)$responseProperties['statusCode']
                        : 200;

                    return new Response(null, $statusCode);
                }

                if ('JsonResponse' === $type) {
                    $statusCode = isset($responseProperties['statusCode'])
                        ? (int)$responseProperties['statusCode']
                        : 200;

                    return new JsonResponse(null, $statusCode);
                }
            }
        }

        return null;
    }
}
