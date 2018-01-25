<?php

namespace SimplyTestable\WebClientBundle\Controller;

use SimplyTestable\WebClientBundle\Exception\UserAccountCardException;
use SimplyTestable\WebClientBundle\Interfaces\Controller\RequiresPrivateUser;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class UserAccountCardController extends Controller implements RequiresPrivateUser
{
    /**
     * @param $stripe_card_token
     *
     * @return JsonResponse
     */
    public function associateAction($stripe_card_token)
    {
        $router = $this->container->get('router');
        $userAccountCardService = $this->get('simplytestable.services.useraccountcardservice');
        $userService = $this->container->get('simplytestable.services.userservice');

        $user = $userService->getUser();

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
    public function getUserSignInRedirectResponse(Request $request)
    {
        $router = $this->container->get('router');

        $redirectUrl = $router->generate('view_user_signin_index', [], UrlGeneratorInterface::ABSOLUTE_URL);

        return new RedirectResponse($redirectUrl);
    }
}
