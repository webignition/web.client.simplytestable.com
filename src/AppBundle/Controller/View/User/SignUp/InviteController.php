<?php

namespace AppBundle\Controller\View\User\SignUp;

use AppBundle\Controller\View\User\AbstractUserController;
use AppBundle\Exception\InvalidAdminCredentialsException;
use AppBundle\Exception\InvalidContentTypeException;
use AppBundle\Services\CacheValidatorService;
use AppBundle\Services\DefaultViewParameters;
use AppBundle\Services\FlashBagValues;
use AppBundle\Services\TeamInviteService;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use AppBundle\Controller\Action\SignUp\Team\InviteController as ActionInviteController;
use Symfony\Component\Routing\RouterInterface;
use Twig_Environment;

class InviteController extends AbstractUserController
{
    /**
     * @var TeamInviteService
     */
    private $teamInviteService;

    /**
     * @param RouterInterface $router
     * @param Twig_Environment $twig
     * @param DefaultViewParameters $defaultViewParameters
     * @param CacheValidatorService $cacheValidator
     * @param FlashBagValues $flashBagValues
     * @param TeamInviteService $teamInviteService
     */
    public function __construct(
        RouterInterface $router,
        Twig_Environment $twig,
        DefaultViewParameters $defaultViewParameters,
        CacheValidatorService $cacheValidator,
        FlashBagValues $flashBagValues,
        TeamInviteService $teamInviteService
    ) {
        parent::__construct($router, $twig, $defaultViewParameters, $cacheValidator, $flashBagValues);

        $this->teamInviteService = $teamInviteService;
    }

    /**
     * @param Request $request
     * @param string $token
     *
     * @return Response
     *
     * @throws InvalidAdminCredentialsException
     * @throws InvalidContentTypeException
     */
    public function indexAction(Request $request, $token)
    {
        $staySignedIn = $request->query->get('stay-signed-in');

        $invite = $this->teamInviteService->getForToken($token);
        $flashBagValues = $this->flashBagValues->get([
            ActionInviteController::FLASH_BAG_INVITE_ACCEPT_ERROR_KEY,
            ActionInviteController::FLASH_BAG_INVITE_ACCEPT_FAILURE_KEY,
        ]);

        $response = $this->cacheValidator->createResponse($request, array_merge(
            [
                'token' => $token,
                'invite' => json_encode($invite),
                'stay_signed_in' => $staySignedIn
            ],
            $flashBagValues
        ));

        if ($this->cacheValidator->isNotModified($response)) {
            return $response;
        }

        $viewData = array_merge(
            [
                'token' => $token,
                'invite' => $invite,
                'stay_signed_in' => $staySignedIn,
            ],
            $flashBagValues
        );

        $view = !empty($invite)
            ? 'user-sign-up-invite.html.twig'
            : 'user-sign-up-invite-invalid-token.html.twig';

        return $this->renderWithDefaultViewParameters($view, $viewData, $response);
    }
}
