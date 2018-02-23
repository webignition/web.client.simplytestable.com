<?php

namespace SimplyTestable\WebClientBundle\Controller;

use SimplyTestable\WebClientBundle\Interfaces\Controller\IEFiltered;
use SimplyTestable\WebClientBundle\Services\DefaultViewParameters;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Twig_Environment;

abstract class BaseViewController implements IEFiltered
{
    /**
     * @var DefaultViewParameters
     */
    protected $defaultViewParameters;

    /**
     * @var Response|RedirectResponse|JsonResponse
     */
    protected $response;

    /**
     * @var Twig_Environment
     */
    protected $twig;

    /**
     * @param Twig_Environment $twig
     * @param DefaultViewParameters $defaultViewParameters
     */
    public function __construct(Twig_Environment $twig, DefaultViewParameters $defaultViewParameters)
    {
        $this->twig = $twig;
        $this->defaultViewParameters = $defaultViewParameters;
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
}
