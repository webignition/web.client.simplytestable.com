<?php

namespace SimplyTestable\WebClientBundle\Controller\View\Test;

use SimplyTestable\WebClientBundle\Controller\BaseViewController;
use SimplyTestable\WebClientBundle\Interfaces\Controller\Test\RequiresValidOwner;
use SimplyTestable\WebClientBundle\Services\UserManager;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

abstract class AbstractRequiresValidOwnerController extends BaseViewController implements RequiresValidOwner
{
    /**
     * {@inheritdoc}
     */
    public function getInvalidOwnerResponse(Request $request)
    {
        $urlViewValuesService = $this->container->get('SimplyTestable\WebClientBundle\Services\UrlViewValuesService');
        $session = $this->container->get('session');
        $router = $this->container->get('router');
        $userManager = $this->container->get(UserManager::class);

        if ($userManager->isLoggedIn()) {
            return $this->render(
                'SimplyTestableWebClientBundle:bs3/Test/Results:not-authorised.html.twig',
                array_merge($this->getDefaultViewParameters(), [
                    'test_id' => $request->attributes->get('test_id'),
                    'website' => $urlViewValuesService->create($request->attributes->get('website')),
                ])
            );
        }

        $redirectParameters = json_encode([
            'route' => 'view_test_progress_index_index',
            'parameters' => [
                'website' => $request->attributes->get('website'),
                'test_id' => $request->attributes->get('test_id')
            ]
        ]);

        $session->getFlashBag()->set('user_signin_error', 'test-not-logged-in');

        return new RedirectResponse($router->generate(
            'view_user_signin_index',
            [
                'redirect' => base64_encode($redirectParameters)
            ],
            UrlGeneratorInterface::ABSOLUTE_URL
        ));
    }
}
