<?php

namespace SimplyTestable\WebClientBundle\Controller\Action\User\Account;

use SimplyTestable\WebClientBundle\Exception\CoreApplicationReadOnlyException;
use SimplyTestable\WebClientBundle\Exception\CoreApplicationRequestException;
use SimplyTestable\WebClientBundle\Exception\InvalidCredentialsException;
use SimplyTestable\WebClientBundle\Exception\UserAccountCardException;
use SimplyTestable\WebClientBundle\Services\UserAccountCardService;
use SimplyTestable\WebClientBundle\Services\UserManager;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Routing\RouterInterface;

class CardController extends AbstractUserAccountController
{
    /**
     * @var UserAccountCardService
     */
    private $userAccountCardService;

    /**
     * @var UserManager
     */
    private $userManager;

    /**
     * @var RouterInterface
     */
    private $router;

    /**
     * @param UserAccountCardService $userAccountCardService
     * @param UserManager $userManager
     * @param RouterInterface $router
     */
    public function __construct(
        UserAccountCardService $userAccountCardService,
        UserManager $userManager,
        RouterInterface $router
    ) {
        $this->userAccountCardService = $userAccountCardService;
        $this->userManager = $userManager;
        $this->router = $router;
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
        if ($this->hasResponse()) {
            return $this->response;
        }

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
            'this_url' => $this->router->generate(
                'view_user_account_index_index',
                [],
                UrlGeneratorInterface::ABSOLUTE_URL
            ),
        ]);
    }
}
