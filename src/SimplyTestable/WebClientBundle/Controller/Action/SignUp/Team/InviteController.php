<?php

namespace SimplyTestable\WebClientBundle\Controller\Action\SignUp\Team;

use SimplyTestable\WebClientBundle\Exception\CoreApplicationReadOnlyException;
use SimplyTestable\WebClientBundle\Exception\CoreApplicationRequestException;
use SimplyTestable\WebClientBundle\Exception\InvalidAdminCredentialsException;
use SimplyTestable\WebClientBundle\Exception\InvalidContentTypeException;
use SimplyTestable\WebClientBundle\Model\User;
use SimplyTestable\WebClientBundle\Services\ResqueQueueService;
use SimplyTestable\WebClientBundle\Services\TeamInviteService;
use SimplyTestable\WebClientBundle\Services\UserManager;
use SimplyTestable\WebClientBundle\Services\UserService;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Routing\RouterInterface;
use webignition\ResqueJobFactory\ResqueJobFactory;

class InviteController
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
     * @var RouterInterface
     */
    private $router;

    /**
     * @param TeamInviteService $teamInviteService
     * @param UserService $userService
     * @param UserManager $userManager
     * @param ResqueQueueService $resqueQueueService
     * @param ResqueJobFactory $resqueJobFactory
     * @param SessionInterface $session
     * @param RouterInterface $router
     */
    public function __construct(
        TeamInviteService $teamInviteService,
        UserService $userService,
        UserManager $userManager,
        ResqueQueueService $resqueQueueService,
        ResqueJobFactory $resqueJobFactory,
        SessionInterface $session,
        RouterInterface $router
    ) {
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
            return new RedirectResponse($this->router->generate(
                'view_user_signup_index_index',
                [],
                UrlGeneratorInterface::ABSOLUTE_URL
            ));
        }

        $invite = $this->teamInviteService->getForToken($token);

        if (empty($invite)) {
            return new RedirectResponse($this->router->generate(
                'view_user_signup_index_index',
                [],
                UrlGeneratorInterface::ABSOLUTE_URL
            ));
        }

        $password = trim($requestData->get('password'));
        if (empty($password)) {
            $this->session->getFlashBag()->set(
                self::FLASH_BAG_INVITE_ACCEPT_ERROR_KEY,
                self::FLASH_BAG_INVITE_ACCEPT_ERROR_MESSAGE_PASSWORD_BLANK
            );

            return new RedirectResponse($this->router->generate(
                'view_user_signup_invite_index',
                [
                    'token' => $token
                ],
                UrlGeneratorInterface::ABSOLUTE_URL
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

            return new RedirectResponse($this->router->generate(
                'view_user_signup_invite_index',
                [
                    'token' => $token
                ],
                UrlGeneratorInterface::ABSOLUTE_URL
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

        $response = new RedirectResponse($this->router->generate(
            'view_dashboard_index_index',
            [],
            UrlGeneratorInterface::ABSOLUTE_URL
        ));

        if ($staySignedIn) {
            $response->headers->setCookie($this->userManager->createUserCookie());
        }

        return $response;
    }
}
