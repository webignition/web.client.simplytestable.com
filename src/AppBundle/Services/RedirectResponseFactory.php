<?php

namespace AppBundle\Services;

use AppBundle\Request\User\SignInRequest;
use AppBundle\Request\User\SignUpRequest;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Routing\RouterInterface;

class RedirectResponseFactory
{
    /**
     * @var RouterInterface
     */
    private $router;

    /**
     * @param RouterInterface $router
     */
    public function __construct(RouterInterface $router)
    {
        $this->router = $router;
    }

    /**
     * @param SignInRequest $signInRequest
     *
     * @return RedirectResponse
     */
    public function createSignInRedirectResponse(SignInRequest $signInRequest)
    {
        $email = $signInRequest->getEmail();
        $staySignedIn = $signInRequest->getStaySignedIn();
        $redirect = $signInRequest->getRedirect();

        if (!empty($email)) {
            $routeParameters[SignInRequest::PARAMETER_EMAIL] = $email;
        }

        $routeParameters[SignInRequest::PARAMETER_STAY_SIGNED_IN] = (int)$staySignedIn;

        if (!empty($redirect)) {
            $routeParameters[SignInRequest::PARAMETER_REDIRECT] = $redirect;
        }

        return new RedirectResponse($this->router->generate('view_user_signin_index', $routeParameters));
    }

    /**
     * @param SignUpRequest $signUpRequest
     *
     * @return RedirectResponse
     */
    public function createSignUpRedirectResponse(SignUpRequest $signUpRequest)
    {
        $email = $signUpRequest->getEmail();
        $plan = $signUpRequest->getPlan();

        $routeParameters = [];

        if (!empty($email)) {
            $routeParameters[SignUpRequest::PARAMETER_EMAIL] = $email;
        }

        $routeParameters[SignUpRequest::PARAMETER_PLAN] = $plan;

        return new RedirectResponse($this->router->generate('view_user_signup_index_index', $routeParameters));
    }
}
