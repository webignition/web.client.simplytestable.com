<?php

namespace SimplyTestable\WebClientBundle\Controller\View\User\ResetPassword;

use SimplyTestable\WebClientBundle\Controller\BaseViewController;
use SimplyTestable\WebClientBundle\Services\CacheValidatorService;
use SimplyTestable\WebClientBundle\Services\DefaultViewParameters;
use SimplyTestable\WebClientBundle\Services\FlashBagValues;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\RouterInterface;
use Twig_Environment;

class IndexController extends BaseViewController
{
    /**
     * @var FlashBagValues
     */
    private $flashBagValues;

    /**
     * @param RouterInterface $router
     * @param Twig_Environment $twig
     * @param DefaultViewParameters $defaultViewParameters
     * @param CacheValidatorService $cacheValidator
     * @param FlashBagValues $flashBagValues
     */
    public function __construct(
        RouterInterface $router,
        Twig_Environment $twig,
        DefaultViewParameters $defaultViewParameters,
        CacheValidatorService $cacheValidator,
        FlashBagValues $flashBagValues
    ) {
        parent::__construct($router, $twig, $defaultViewParameters, $cacheValidator);

        $this->flashBagValues = $flashBagValues;
    }

    /**
     * @param Request $request
     *
     * @return Response
     */
    public function indexAction(Request $request)
    {
        $viewData = array_merge([
            'email' => trim($request->query->get('email')),
        ], $this->flashBagValues->get([
            'user_reset_password_error',
            'user_reset_password_confirmation',
        ]));

        $response = $this->cacheValidator->createResponse($request, $viewData);

        if ($this->cacheValidator->isNotModified($response)) {
            return $response;
        }

        return $this->renderWithDefaultViewParameters(
            'SimplyTestableWebClientBundle:bs3/User/ResetPassword/Index:index.html.twig',
            $viewData,
            $response
        );
    }
}
