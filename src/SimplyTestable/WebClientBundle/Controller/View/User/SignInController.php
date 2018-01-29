<?php

namespace SimplyTestable\WebClientBundle\Controller\View\User;

use SimplyTestable\WebClientBundle\Controller\BaseViewController;
use SimplyTestable\WebClientBundle\Interfaces\Controller\IEFiltered;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class SignInController extends BaseViewController implements IEFiltered
{
    /**
     * @param Request $request
     *
     * @return RedirectResponse|Response
     */
    public function indexAction(Request $request)
    {
        $cacheValidatorService = $this->container->get('simplytestable.services.cachevalidator');
        $userService = $this->container->get('simplytestable.services.userservice');
        $flashBagValuesService = $this->container->get('simplytestable.services.flashbagvalues');
        $templating = $this->container->get('templating');
        $router = $this->container->get('router');

        $user = $userService->getUser();
        $isLoggedIn = !$userService->isPublicUser($user);

        if ($isLoggedIn) {
            $redirectUrl = $router->generate(
                'view_dashboard_index_index',
                [],
                UrlGeneratorInterface::ABSOLUTE_URL
            );

            return new RedirectResponse($redirectUrl);
        }

        $requestData = $request->query;
        $email = $requestData->get('email');
        $userSignInError = $flashBagValuesService->getSingle('user_signin_error');
        $userSignInConfirmation = $flashBagValuesService->getSingle('user_signin_confirmation');
        $redirect = $requestData->get('redirect');
        $staySignedIn = $requestData->get('stay-signed-in');

        $viewData = [
            'email' => $email,
            'user_signin_error' => $userSignInError,
            'user_signin_confirmation' => $userSignInConfirmation,
            'redirect' => $redirect,
            'stay_signed_in' => $staySignedIn,
        ];

        $response = $cacheValidatorService->createResponse($request, $viewData);

        if ($cacheValidatorService->isNotModified($response)) {
            return $response;
        }

        $content = $templating->render(
            'SimplyTestableWebClientBundle:bs3/User/SignIn:index.html.twig',
            array_merge($this->getDefaultViewParameters(), $viewData)
        );

        $response->setContent($content);

        return $response;
    }
}