<?php

namespace SimplyTestable\WebClientBundle\Controller\View\User\SignUp;

use SimplyTestable\WebClientBundle\Exception\CoreApplicationAdminRequestException;
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
     *
     * @throws CoreApplicationAdminRequestException
     */
    public function indexAction(Request $request)
    {
        $teamInviteService = $this->container->get('simplytestable.services.teaminviteservice');
        $cacheableResponseService = $this->get('simplytestable.services.cacheableResponseService');
        $flashBagValuesService = $this->container->get('simplytestable.services.flashbagvalues');

        $token = trim($request->attributes->get('token'));
        $staySignedIn = $request->query->get('stay-signed-in');

        $invite = $teamInviteService->getForToken($token);

        $viewData = array_merge(
            [
                'token' => $token,
                'invite' => $invite,
                'has_invite' => !empty($invite),
                'stay_signed_in' => $staySignedIn,
            ],
            $flashBagValuesService->get([
                ActionInviteController::FLASH_BAG_INVITE_ACCEPT_ERROR_KEY,
                ActionInviteController::FLASH_BAG_INVITE_ACCEPT_FAILURE_KEY,
            ])
        );

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
        $flashBagValuesService = $this->container->get('simplytestable.services.flashbagvalues');

        $token = trim($this->getRequest()->attributes->get('token'));
        $invite = $teamInviteService->getForToken($token);

        return array_merge(
            [
                'token' => $token,
                'invite' => json_encode($invite),
                'has_invite' => json_encode(!empty($invite)),
                'stay_signed_in' => $this->getRequest()->query->get('stay-signed-in')
            ],
            $flashBagValuesService->get([
                ActionInviteController::FLASH_BAG_INVITE_ACCEPT_ERROR_KEY,
                ActionInviteController::FLASH_BAG_INVITE_ACCEPT_FAILURE_KEY,
            ])
        );
    }
}
