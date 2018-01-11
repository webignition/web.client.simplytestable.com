<?php

namespace SimplyTestable\WebClientBundle\Controller\View\User\SignUp;

use SimplyTestable\WebClientBundle\Interfaces\Controller\IEFiltered;
use SimplyTestable\WebClientBundle\Controller\View\CacheableViewController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use SimplyTestable\WebClientBundle\Controller\Action\SignUp\Team\InviteController as ActionInviteController;

class InviteController extends CacheableViewController implements IEFiltered
{
    /**
     * @param Request $request
     *
     * @return Response
     */
    public function indexAction(Request $request)
    {
        $teamInviteService = $this->container->get('simplytestable.services.teaminviteservice');
        $session = $this->container->get('session');
        $cacheableResponseService = $this->get('simplytestable.services.cacheableResponseService');

        $token = trim($request->attributes->get('token'));
        $staySignedIn = $request->query->get('stay-signed-in');

        $invite = $teamInviteService->getForToken($token);

        $flashBag = $session->getFlashBag();

        $viewData = [
            ActionInviteController::FLASH_BAG_INVITE_ACCEPT_ERROR_KEY =>
                $flashBag->get(ActionInviteController::FLASH_BAG_INVITE_ACCEPT_ERROR_KEY),
            ActionInviteController::FLASH_BAG_INVITE_ACCEPT_FAILURE_KEY =>
                $flashBag->get(ActionInviteController::FLASH_BAG_INVITE_ACCEPT_FAILURE_KEY),
            'token' => $token,
            'invite' => $invite,
            'has_invite' => !empty($invite),
            'stay_signed_in' => $staySignedIn,
        ];

        return $cacheableResponseService->getCachableResponse(
            $request,
            parent::render(
                'SimplyTestableWebClientBundle:bs3/User/SignUp/Invite:index.html.twig',
                array_merge($this->getDefaultViewParameters(), $viewData)
            )
        );
    }

    /**
     * {@inheritdoc}
     */
    public function getCacheValidatorParameters()
    {
        $container = $this->container;
        $teamInviteService = $container->get('simplytestable.services.teaminviteservice');
        $session = $container->get('session');

        $token = trim($this->getRequest()->attributes->get('token'));
        $invite = $teamInviteService->getForToken($token);
        $flashBag = $session->getFlashBag();

        $inviteAcceptErrorMessages = $flashBag->peek(
            ActionInviteController::FLASH_BAG_INVITE_ACCEPT_ERROR_KEY,
            ['']
        );

        $inviteAcceptFailureMessages = $flashBag->peek(
            ActionInviteController::FLASH_BAG_INVITE_ACCEPT_FAILURE_KEY,
            ['']
        );

        return [
            'invite_accept_error' => $inviteAcceptErrorMessages[0],
            'invite_accept_failure' => $inviteAcceptFailureMessages[0],
            'token' => $token,
            'invite' => json_encode($invite),
            'has_invite' => json_encode(!empty($invite)),
            'stay_signed_in' => $this->getRequest()->query->get('stay-signed-in')
        ];
    }
}
