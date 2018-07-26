<?php

namespace App\Controller\Action\User\Account;

use App\Exception\CoreApplicationReadOnlyException;
use App\Exception\CoreApplicationRequestException;
use App\Exception\InvalidCredentialsException;
use App\Exception\UserAccountCardException;
use App\Services\UserAccountCardService;
use App\Services\UserManager;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Session\Flash\FlashBagInterface;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Routing\RouterInterface;

class CardController extends AbstractUserAccountController
{
    /**
     * @var UserAccountCardService
     */
    private $userAccountCardService;

    public function __construct(
        RouterInterface $router,
        UserManager $userManager,
        FlashBagInterface $flashBag,
        UserAccountCardService $userAccountCardService
    ) {
        parent::__construct($router, $userManager, $flashBag);

        $this->userAccountCardService = $userAccountCardService;
    }

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
        $user = $this->userManager->getUser();

        try {
            $this->userAccountCardService->associate($user, $stripe_card_token);
        } catch (UserAccountCardException $userAccountCardException) {
            return new JsonResponse([
                'user_account_card_exception_message' => $userAccountCardException->getMessage(),
                'user_account_card_exception_param' => $userAccountCardException->getParam(),
                'user_account_card_exception_code' => $userAccountCardException->getStripeCode(),
            ]);
        }

        return new JsonResponse([
            'this_url' => $this->generateUrl(
                'view_user_account',
                [],
                UrlGeneratorInterface::ABSOLUTE_URL
            ),
        ]);
    }
}
