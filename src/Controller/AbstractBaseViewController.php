<?php

namespace App\Controller;

use App\Services\CacheableResponseFactory;
use App\Services\DefaultViewParameters;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\RouterInterface;
use Twig_Environment;

abstract class AbstractBaseViewController extends AbstractController
{
    /**
     * @var DefaultViewParameters
     */
    private $defaultViewParameters;

    /**
     * @var Twig_Environment
     */
    private $twig;

    /**
     * @var CacheableResponseFactory
     */
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

    /**
     * @param string $view
     * @param array $parameters
     * @param Response|null $response
     *
     * @return Response
     *
     * @throws \Twig_Error_Loader
     * @throws \Twig_Error_Runtime
     * @throws \Twig_Error_Syntax
     */
    protected function render($view, array $parameters = array(), Response $response = null)
    {
        if (empty($response)) {
            $response = new Response();
        }

        $content = $this->twig->render($view, $parameters);

        $response->setContent($content);

        return $response;
    }

    /**
     * @param string $view
     * @param array $parameters
     * @param Response|null $response
     *
     * @return Response
     *
     * @throws \Twig_Error_Loader
     * @throws \Twig_Error_Runtime
     * @throws \Twig_Error_Syntax
     */
    protected function renderWithDefaultViewParameters($view, array $parameters = array(), Response $response = null)
    {
        return $this->render(
            $view,
            array_merge($this->defaultViewParameters->getDefaultViewParameters(), $parameters),
            $response
        );
    }
}
