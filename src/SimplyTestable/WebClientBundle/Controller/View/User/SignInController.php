<?php

namespace SimplyTestable\WebClientBundle\Controller\View\User;

use SimplyTestable\WebClientBundle\Services\CacheValidatorService;
use SimplyTestable\WebClientBundle\Services\DefaultViewParameters;
use SimplyTestable\WebClientBundle\Services\FlashBagValues;
use SimplyTestable\WebClientBundle\Services\SystemUserService;
use SimplyTestable\WebClientBundle\Services\UserManager;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Routing\RouterInterface;
use Twig_Environment;

class SignInController extends AbstractUserController
{
    /**
     * @var UserManager
     */
    private $userManager;

    /**
     * @param RouterInterface $router
     * @param Twig_Environment $twig
     * @param DefaultViewParameters $defaultViewParameters
     * @param CacheValidatorService $cacheValidator
     * @param FlashBagValues $flashBagValues
     * @param UserManager $userManager
     */
    public function __construct(
        RouterInterface $router,
        Twig_Environment $twig,
        DefaultViewParameters $defaultViewParameters,
        CacheValidatorService $cacheValidator,
        FlashBagValues $flashBagValues,
        UserManager $userManager
    ) {
        parent::__construct($router, $twig, $defaultViewParameters, $cacheValidator, $flashBagValues);

        $this->userManager = $userManager;
    }

    /**
     * @param Request $request
     *
     * @return RedirectResponse|Response
     */
    public function indexAction(Request $request)
    {
        $user = $this->userManager->getUser();

        if (!SystemUserService::isPublicUser($user)) {
            return new RedirectResponse($this->router->generate(
                'view_dashboard_index_index',
                [],
                UrlGeneratorInterface::ABSOLUTE_URL
            ));
        }

        $requestData = $request->query;
        $email = $requestData->get('email');
        $userSignInError = $this->flashBagValues->getSingle('user_signin_error');
        $userSignInConfirmation = $this->flashBagValues->getSingle('user_signin_confirmation');
        $redirect = $requestData->get('redirect');
        $staySignedIn = $requestData->get('stay-signed-in');

        $viewData = [
            'email' => $email,
            'user_signin_error' => $userSignInError,
            'user_signin_confirmation' => $userSignInConfirmation,
            'redirect' => $redirect,
            'stay_signed_in' => $staySignedIn,
        ];

        $response = $this->cacheValidator->createResponse($request, $viewData);

        if ($this->cacheValidator->isNotModified($response)) {
            return $response;
        }

        return $this->renderWithDefaultViewParameters(
            'SimplyTestableWebClientBundle:bs3/User/SignIn:index.html.twig',
            $viewData,
            $response
        );
    }
}
