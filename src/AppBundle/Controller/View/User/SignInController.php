<?php

namespace AppBundle\Controller\View\User;

use AppBundle\Request\User\SignInRequest;
use AppBundle\Services\CacheValidatorService;
use AppBundle\Services\DefaultViewParameters;
use AppBundle\Services\FlashBagValues;
use AppBundle\Services\SystemUserService;
use AppBundle\Services\UserManager;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\RouterInterface;
use Twig_Environment;

class SignInController extends AbstractUserController
{
    const FIELD_EMAIL = 'email';
    const FIELD_PASSWORD = 'password';

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
            return new RedirectResponse($this->generateUrl('view_dashboard_index_index'));
        }

        $requestData = $request->query;
        $email = $requestData->get(SignInRequest::PARAMETER_EMAIL);
        $errorField = $this->flashBagValues->getSingle('user_signin_error_field');
        $errorState = $this->flashBagValues->getSingle('user_signin_error_state');
        $userSignInError = $this->flashBagValues->getSingle('user_signin_error');
        $userSignInConfirmation = $this->flashBagValues->getSingle('user_signin_confirmation');
        $redirect = $requestData->get(SignInRequest::PARAMETER_REDIRECT);
        $staySignedIn = $requestData->get(SignInRequest::PARAMETER_STAY_SIGNED_IN);

        $selectedField = empty($email) || SignInRequest::PARAMETER_EMAIL === $errorField || !empty($userSignInError)
            ? SignInRequest::PARAMETER_EMAIL
            : SignInRequest::PARAMETER_PASSWORD;

        $viewData = [
            'email' => $email,
            'error_field' => $errorField,
            'error_state' => $errorState,
            'user_signin_error' => $userSignInError,
            'user_signin_confirmation' => $userSignInConfirmation,
            'redirect' => $redirect,
            'stay_signed_in' => $staySignedIn,
            'selected_field' => $selectedField,
        ];

        $response = $this->cacheValidator->createResponse($request, $viewData);

        if ($this->cacheValidator->isNotModified($response)) {
            return $response;
        }

        return $this->renderWithDefaultViewParameters('user-sign-in.html.twig', $viewData, $response);
    }
}