<?php

namespace App\Services;

use Symfony\Component\HttpFoundation\Response;
use Twig_Environment;

class ViewRenderService
{
    private $twig;
    private $defaultViewParameters;

    public function __construct(Twig_Environment $twig, DefaultViewParameters $defaultViewParameters)
    {
        $this->twig = $twig;
        $this->defaultViewParameters = $defaultViewParameters;
    }

    public function renderResponse(string $view, array $parameters = [], Response $response = null): Response
    {
        if (empty($response)) {
            $response = new Response();
        }

        $content = $this->twig->render($view, $parameters);

        $response->setContent($content);

        return $response;
    }

    public function renderResponseWithDefaultViewParameters(
        string $view,
        array $parameters = [],
        Response $response = null
    ): Response {
        return $this->renderResponse(
            $view,
            array_merge($this->defaultViewParameters->getDefaultViewParameters(), $parameters),
            $response
        );
    }
}
