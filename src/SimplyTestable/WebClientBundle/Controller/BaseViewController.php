<?php

namespace SimplyTestable\WebClientBundle\Controller;

use SimplyTestable\WebClientBundle\Interfaces\Controller\IEFiltered;
use SimplyTestable\WebClientBundle\Services\CacheValidatorService;
use SimplyTestable\WebClientBundle\Services\DefaultViewParameters;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\RouterInterface;
use Twig_Environment;

abstract class BaseViewController extends AbstractController implements IEFiltered
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
     * @var Response|RedirectResponse|JsonResponse
     */
    protected $response;

    /**
     * @var CacheValidatorService
     */
    protected $cacheValidator;

    /**
     * @param RouterInterface $router
     * @param Twig_Environment $twig
     * @param DefaultViewParameters $defaultViewParameters
     * @param CacheValidatorService $cacheValidator
     */
    public function __construct(
        RouterInterface $router,
        Twig_Environment $twig,
        DefaultViewParameters $defaultViewParameters,
        CacheValidatorService $cacheValidator
    ) {
        parent::__construct($router);

        $this->twig = $twig;
        $this->defaultViewParameters = $defaultViewParameters;
        $this->cacheValidator = $cacheValidator;
    }

    /**
     * @param Response $response
     */
    public function setResponse(Response $response)
    {
        $this->response = $response;
    }

    /**
     * @return bool
     */
    public function hasResponse()
    {
        return !empty($this->response);
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
