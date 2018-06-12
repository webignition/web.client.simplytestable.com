<?php

namespace SimplyTestable\WebClientBundle\Controller\View\User\SignUp;

use SimplyTestable\WebClientBundle\Controller\View\User\AbstractUserController;
use SimplyTestable\WebClientBundle\Exception\InvalidAdminCredentialsException;
use SimplyTestable\WebClientBundle\Exception\InvalidContentTypeException;
use SimplyTestable\WebClientBundle\Services\CacheValidatorService;
use SimplyTestable\WebClientBundle\Services\DefaultViewParameters;
use SimplyTestable\WebClientBundle\Services\FlashBagValues;
use SimplyTestable\WebClientBundle\Services\TeamInviteService;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use SimplyTestable\WebClientBundle\Controller\Action\SignUp\Team\InviteController as ActionInviteController;
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
            ? 'SimplyTestableWebClientBundle:bs3/User/SignUp/Invite:index.html.twig'
            : 'SimplyTestableWebClientBundle:bs3/User/SignUp/Invite:invalid.html.twig';

        return $this->renderWithDefaultViewParameters($view, $viewData, $response);
    }
}
