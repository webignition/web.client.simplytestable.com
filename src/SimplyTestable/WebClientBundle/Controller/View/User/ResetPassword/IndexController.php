<?php

namespace SimplyTestable\WebClientBundle\Controller\View\User\ResetPassword;

use SimplyTestable\WebClientBundle\Controller\BaseViewController;
use SimplyTestable\WebClientBundle\Interfaces\Controller\IEFiltered;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class IndexController extends BaseViewController implements IEFiltered
{
    /**
     * @param Request $request
     *
     * @return Response
     */
    public function indexAction(Request $request)
    {
        $cacheValidatorService = $this->container->get('simplytestable.services.cachevalidator');
        $flashBagValuesService = $this->container->get('simplytestable.services.flashbagvalues');
        $templating = $this->container->get('templating');

        $viewData = array_merge([
            'email' => trim($request->query->get('email')),
        ], $flashBagValuesService->get([
            'user_reset_password_error',
            'user_reset_password_confirmation',
        ]));

        $response = $cacheValidatorService->createResponse($request, $viewData);

        if ($cacheValidatorService->isNotModified($response)) {
            return $response;
        }

        $content = $templating->render(
            'SimplyTestableWebClientBundle:bs3/User/ResetPassword/Index:index.html.twig',
            array_merge($this->getDefaultViewParameters(), $viewData)
        );

        $response->setContent($content);

        return $response;
    }
}
