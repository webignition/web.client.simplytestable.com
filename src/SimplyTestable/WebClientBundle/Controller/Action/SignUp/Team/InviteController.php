<?php

namespace SimplyTestable\WebClientBundle\Controller\Action\SignUp\Team;

use SimplyTestable\WebClientBundle\Exception\CoreApplicationReadOnlyException;
use SimplyTestable\WebClientBundle\Exception\CoreApplicationRequestException;
use SimplyTestable\WebClientBundle\Exception\InvalidAdminCredentialsException;
use SimplyTestable\WebClientBundle\Exception\InvalidContentTypeException;
use SimplyTestable\WebClientBundle\Model\User;
use SimplyTestable\WebClientBundle\Services\UserManager;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use webignition\ResqueJobFactory\ResqueJobFactory;

class InviteController extends Controller
{
    const ONE_YEAR_IN_SECONDS = 31536000;

    const FLASH_BAG_INVITE_ACCEPT_ERROR_KEY = 'invite_accept_error';
    const FLASH_BAG_INVITE_ACCEPT_ERROR_MESSAGE_PASSWORD_BLANK = 'blank-password';
    const FLASH_BAG_INVITE_ACCEPT_ERROR_MESSAGE_FAILURE = 'failure';

    const FLASH_BAG_INVITE_ACCEPT_FAILURE_KEY = 'invite_accept_failure';

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
        $teamInviteService = $this->container->get('simplytestable.services.teaminviteservice');
        $session = $this->container->get('session');
        $userService = $this->container->get('simplytestable.services.userservice');
        $resqueQueueService = $this->container->get('simplytestable.services.resque.queueservice');
        $resqueJobFactory = $this->container->get(ResqueJobFactory::class);
        $userManager = $this->container->get(UserManager::class);
        $router = $this->container->get('router');

        $requestData = $request->request;

        if (empty($token)) {
            return new RedirectResponse($router->generate(
                'view_user_signup_index_index',
                [],
                UrlGeneratorInterface::ABSOLUTE_URL
            ));
        }

        $invite = $teamInviteService->getForToken($token);

        if (empty($invite)) {
            return new RedirectResponse($router->generate(
                'view_user_signup_index_index',
                [],
                UrlGeneratorInterface::ABSOLUTE_URL
            ));
        }

        $password = trim($requestData->get('password'));
        if (empty($password)) {
            $session->getFlashBag()->set(
                self::FLASH_BAG_INVITE_ACCEPT_ERROR_KEY,
                self::FLASH_BAG_INVITE_ACCEPT_ERROR_MESSAGE_PASSWORD_BLANK
            );

            return new RedirectResponse($router->generate(
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
            $userService->activateAndAccept($invite, $password);
            $activateAndAcceptIsSuccessful = true;
        } catch (CoreApplicationReadOnlyException $coreApplicationReadOnlyException) {
            $activateAndAcceptFailureCode = 503;
        } catch (CoreApplicationRequestException $coreApplicationRequestException) {
            $activateAndAcceptFailureCode = $coreApplicationRequestException->getCode();
        }

        if (false === $activateAndAcceptIsSuccessful) {
            $session->getFlashBag()->set(
                self::FLASH_BAG_INVITE_ACCEPT_ERROR_KEY,
                self::FLASH_BAG_INVITE_ACCEPT_ERROR_MESSAGE_FAILURE
            );

            $session->getFlashBag()->set(
                self::FLASH_BAG_INVITE_ACCEPT_FAILURE_KEY,
                $activateAndAcceptFailureCode
            );

            return new RedirectResponse($router->generate(
                'view_user_signup_invite_index',
                [
                    'token' => $token
                ],
                UrlGeneratorInterface::ABSOLUTE_URL
            ));
        }

        $resqueQueueService->enqueue(
            $resqueJobFactory->create(
                'email-list-subscribe',
                [
                    'listId' => 'announcements',
                    'email' => $invite->getUser(),
                ]
            )
        );

        $resqueQueueService->enqueue(
            $resqueJobFactory->create(
                'email-list-subscribe',
                [
                    'listId' => 'introduction',
                    'email' => $invite->getUser(),
                ]
            )
        );

        $user = new User($invite->getUser(), $password);
        $userManager->setUser($user);

        $staySignedIn = !empty(trim($requestData->get('stay-signed-in')));

        $response = new RedirectResponse($router->generate(
            'view_dashboard_index_index',
            [],
            UrlGeneratorInterface::ABSOLUTE_URL
        ));

        if ($staySignedIn) {
            $response->headers->setCookie($userManager->createUserCookie());
        }

        return $response;
    }
}
