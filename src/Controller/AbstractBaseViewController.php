<?php

namespace App\Controller;

use App\Services\CacheableResponseFactory;
use App\Services\DefaultViewParameters;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\RouterInterface;
use Twig_Environment;

abstract class AbstractBaseViewController extends AbstractController
{
    private $defaultViewParameters;
    private $twig;
    protected $cacheableResponseFactory;

    public function __construct(
        RouterInterface $router,
        Twig_Environment $twig,
        DefaultViewParameters $defaultViewParameters,
        CacheableResponseFactory $cacheableResponseFactory
    ) {
        parent::__construct($router);

        $this->twig = $twig;
        $this->defaultViewParameters = $defaultViewParameters;
        $this->cacheableResponseFactory = $cacheableResponseFactory;
    }

    protected function render(string $view, array $parameters = [], Response $response = null): Response
    {
        if (empty($response)) {
            $response = new Response();
        }

        $content = $this->twig->render($view, $parameters);

        $response->setContent($content);

        return $response;
    }

    protected function renderWithDefaultViewParameters(
        string $view,
        array $parameters = [],
        Response $response = null
    ): Response {
        return $this->render(
            $view,
            array_merge($this->defaultViewParameters->getDefaultViewParameters(), $parameters),
            $response
        );
    }
}
