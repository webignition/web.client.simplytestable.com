<?php

namespace SimplyTestable\WebClientBundle\Services;

use SimplyTestable\WebClientBundle\Request\User\SignInRequest;
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
}
