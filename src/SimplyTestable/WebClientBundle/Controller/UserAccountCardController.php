<?php

namespace SimplyTestable\WebClientBundle\Controller;

use SimplyTestable\WebClientBundle\Exception\CoreApplicationReadOnlyException;
use SimplyTestable\WebClientBundle\Exception\CoreApplicationRequestException;
use SimplyTestable\WebClientBundle\Exception\InvalidCredentialsException;
use SimplyTestable\WebClientBundle\Exception\UserAccountCardException;
use SimplyTestable\WebClientBundle\Interfaces\Controller\RequiresPrivateUser;
use SimplyTestable\WebClientBundle\Services\UserAccountCardService;
use SimplyTestable\WebClientBundle\Services\UserManager;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Routing\RouterInterface;

class UserAccountCardController extends Controller implements RequiresPrivateUser
{
    /**
     * @var Response|RedirectResponse|JsonResponse
     */
    protected $response;

    /**
     * @param $stripe_card_token
     *
     * @return JsonResponse
     *
     * @throws CoreApplicationReadOnlyException
     * @throws CoreApplicationRequestException
     * @throws InvalidCredentialsException
     */
    public function associateAction($stripe_card_token)
    {
        if ($this->hasResponse()) {
            return $this->response;
        }

        $router = $this->container->get('router');
        $userAccountCardService = $this->get(UserAccountCardService::class);
        $userManager = $this->container->get(UserManager::class);

        $user = $userManager->getUser();

        try {
            $userAccountCardService->associate($user, $stripe_card_token);
        } catch (UserAccountCardException $userAccountCardException) {
            return new JsonResponse([
                'user_account_card_exception_message' => $userAccountCardException->getMessage(),
                'user_account_card_exception_param' => $userAccountCardException->getParam(),
                'user_account_card_exception_code' => $userAccountCardException->getStripeCode(),
            ]);
        }

        return new JsonResponse([
            'this_url' => $router->generate(
                'view_user_account_index_index',
                [],
                UrlGeneratorInterface::ABSOLUTE_URL
            ),
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function getUserSignInRedirectResponse(RouterInterface $router, Request $request)
    {
        return new RedirectResponse($router->generate(
            'view_user_signin_index',
            [],
            UrlGeneratorInterface::ABSOLUTE_URL
        ));
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
