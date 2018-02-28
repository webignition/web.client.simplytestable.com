<?php

namespace SimplyTestable\WebClientBundle\Controller\Action\SignUp\Team;

use SimplyTestable\WebClientBundle\Controller\AbstractController;
use SimplyTestable\WebClientBundle\Exception\CoreApplicationReadOnlyException;
use SimplyTestable\WebClientBundle\Exception\CoreApplicationRequestException;
use SimplyTestable\WebClientBundle\Exception\InvalidAdminCredentialsException;
use SimplyTestable\WebClientBundle\Exception\InvalidContentTypeException;
use SimplyTestable\WebClientBundle\Services\ResqueQueueService;
use SimplyTestable\WebClientBundle\Services\TeamInviteService;
use SimplyTestable\WebClientBundle\Services\UserManager;
use SimplyTestable\WebClientBundle\Services\UserService;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\RouterInterface;
use webignition\ResqueJobFactory\ResqueJobFactory;
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
     * @var ResqueJobFactory
     */
    private $resqueJobFactory;

    /**
     * @var Session
     */
    private $session;

    /**
     * @param RouterInterface $router
     * @param TeamInviteService $teamInviteService
     * @param UserService $userService
     * @param UserManager $userManager
     * @param ResqueQueueService $resqueQueueService
     * @param ResqueJobFactory $resqueJobFactory
     * @param SessionInterface $session
     */
    public function __construct(
        RouterInterface $router,
        TeamInviteService $teamInviteService,
        UserService $userService,
        UserManager $userManager,
        ResqueQueueService $resqueQueueService,
        ResqueJobFactory $resqueJobFactory,
        SessionInterface $session
    ) {
        parent::__construct($router);

        $this->teamInviteService = $teamInviteService;
        $this->userService = $userService;
        $this->userManager = $userManager;
        $this->resqueQueueService = $resqueQueueService;
        $this->resqueJobFactory = $resqueJobFactory;
        $this->session = $session;
        $this->router = $router;
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
            return new RedirectResponse($this->generateUrl('view_user_signup_index_index'));
        }

        $invite = $this->teamInviteService->getForToken($token);

        if (empty($invite)) {
            return new RedirectResponse($this->generateUrl('view_user_signup_index_index'));
        }

        $password = trim($requestData->get('password'));
        if (empty($password)) {
            $this->session->getFlashBag()->set(
                self::FLASH_BAG_INVITE_ACCEPT_ERROR_KEY,
                self::FLASH_BAG_INVITE_ACCEPT_ERROR_MESSAGE_PASSWORD_BLANK
            );

            return new RedirectResponse($this->generateUrl(
                'view_user_signup_invite_index',
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
            $this->session->getFlashBag()->set(
                self::FLASH_BAG_INVITE_ACCEPT_ERROR_KEY,
                self::FLASH_BAG_INVITE_ACCEPT_ERROR_MESSAGE_FAILURE
            );

            $this->session->getFlashBag()->set(
                self::FLASH_BAG_INVITE_ACCEPT_FAILURE_KEY,
                $activateAndAcceptFailureCode
            );

            return new RedirectResponse($this->generateUrl(
                'view_user_signup_invite_index',
                [
                    'token' => $token
                ]
            ));
        }

        $this->resqueQueueService->enqueue(
            $this->resqueJobFactory->create(
                'email-list-subscribe',
                [
                    'listId' => 'announcements',
                    'email' => $invite->getUser(),
                ]
            )
        );

        $this->resqueQueueService->enqueue(
            $this->resqueJobFactory->create(
                'email-list-subscribe',
                [
                    'listId' => 'introduction',
                    'email' => $invite->getUser(),
                ]
            )
        );

        $user = new User($invite->getUser(), $password);
        $this->userManager->setUser($user);

        $staySignedIn = !empty(trim($requestData->get('stay-signed-in')));

        $response = new RedirectResponse($this->generateUrl('view_dashboard_index_index'));

        if ($staySignedIn) {
            $response->headers->setCookie($this->userManager->createUserCookie());
        }

        return $response;
    }
}
