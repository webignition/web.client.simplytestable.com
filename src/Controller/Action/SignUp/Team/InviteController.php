<?php

namespace App\Controller\Action\SignUp\Team;

use App\Controller\AbstractController;
use App\Exception\CoreApplicationReadOnlyException;
use App\Exception\CoreApplicationRequestException;
use App\Exception\InvalidAdminCredentialsException;
use App\Exception\InvalidContentTypeException;
use App\Resque\Job\EmailListSubscribeJob;
use App\Services\ResqueQueueService;
use App\Services\TeamInviteService;
use App\Services\UserManager;
use App\Services\UserService;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Flash\FlashBagInterface;
use Symfony\Component\Routing\RouterInterface;
use webignition\SimplyTestableUserModel\User;

class InviteController extends AbstractController
{
    const ONE_YEAR_IN_SECONDS = 31536000;

    const FLASH_BAG_INVITE_ACCEPT_ERROR_KEY = 'invite_accept_error';
    const FLASH_BAG_INVITE_ACCEPT_ERROR_MESSAGE_PASSWORD_BLANK = 'blank-password';
    const FLASH_BAG_INVITE_ACCEPT_ERROR_MESSAGE_FAILURE = 'failure';

    const FLASH_BAG_INVITE_ACCEPT_FAILURE_KEY = 'invite_accept_failure';

    /**
     * @var TeamInviteService
     */
    private $teamInviteService;

    /**
     * @var UserService
     */
    private $userService;

    /**
     * @var UserManager
     */
    private $userManager;

    /**
     * @var ResqueQueueService
     */
    private $resqueQueueService;

    /**
     * @var FlashBagInterface
     */
    private $flashBag;

    public function __construct(
        RouterInterface $router,
        TeamInviteService $teamInviteService,
        UserService $userService,
        UserManager $userManager,
        ResqueQueueService $resqueQueueService,
        FlashBagInterface $flashBag
    ) {
        parent::__construct($router);

        $this->teamInviteService = $teamInviteService;
        $this->userService = $userService;
        $this->userManager = $userManager;
        $this->resqueQueueService = $resqueQueueService;
        $this->router = $router;
        $this->flashBag = $flashBag;
    }

    /**
     * @param Request $request
     * @param string $token
     *
     * @return RedirectResponse
     *
     * @throws \CredisException
     * @throws \Exception
     * @throws InvalidAdminCredentialsException
     * @throws InvalidContentTypeException
     */
    public function acceptAction(Request $request, $token)
    {
        $requestData = $request->request;

        if (empty($token)) {
            return new RedirectResponse($this->generateUrl('view_user_sign_up_request'));
        }

        $invite = $this->teamInviteService->getForToken($token);

        if (empty($invite)) {
            return new RedirectResponse($this->generateUrl('view_user_sign_up_request'));
        }

        $password = trim($requestData->get('password'));
        if (empty($password)) {
            $this->flashBag->set(
                self::FLASH_BAG_INVITE_ACCEPT_ERROR_KEY,
                self::FLASH_BAG_INVITE_ACCEPT_ERROR_MESSAGE_PASSWORD_BLANK
            );

            return new RedirectResponse($this->generateUrl(
                'view_user_sign_up_invite',
                [
                    'token' => $token
                ]
            ));
        }

        $activateAndAcceptIsSuccessful = false;
        $activateAndAcceptFailureCode = null;

        try {
            $this->userService->activateAndAccept($invite, $password);
            $activateAndAcceptIsSuccessful = true;
        } catch (CoreApplicationReadOnlyException $coreApplicationReadOnlyException) {
            $activateAndAcceptFailureCode = 503;
        } catch (CoreApplicationRequestException $coreApplicationRequestException) {
            $activateAndAcceptFailureCode = $coreApplicationRequestException->getCode();
        }

        if (false === $activateAndAcceptIsSuccessful) {
            $this->flashBag->set(
                self::FLASH_BAG_INVITE_ACCEPT_ERROR_KEY,
                self::FLASH_BAG_INVITE_ACCEPT_ERROR_MESSAGE_FAILURE
            );

            $this->flashBag->set(self::FLASH_BAG_INVITE_ACCEPT_FAILURE_KEY, $activateAndAcceptFailureCode);

            return new RedirectResponse($this->generateUrl(
                'view_user_sign_up_invite',
                [
                    'token' => $token
                ]
            ));
        }

        $this->resqueQueueService->enqueue(new EmailListSubscribeJob([
            'listId' => 'announcements',
            'email' => $invite->getUser(),
        ]));

        $this->resqueQueueService->enqueue(new EmailListSubscribeJob([
            'listId' => 'introduction',
            'email' => $invite->getUser(),
        ]));

        $user = new User($invite->getUser(), $password);
        $this->userManager->setUser($user);

        $staySignedIn = !empty(trim($requestData->get('stay-signed-in')));

        $response = new RedirectResponse($this->generateUrl('view_dashboard'));

        if ($staySignedIn) {
            $response->headers->setCookie($this->userManager->createUserCookie());
        }

        return $response;
    }
}
