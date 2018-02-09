<?php

namespace SimplyTestable\WebClientBundle\Controller\Action\SignUp\Team;

use SimplyTestable\WebClientBundle\Exception\InvalidAdminCredentialsException;
use SimplyTestable\WebClientBundle\Exception\InvalidContentTypeException;
use SimplyTestable\WebClientBundle\Model\User;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Cookie;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

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
        $resqueJobFactory = $this->container->get('simplytestable.services.resque.jobfactoryservice');
        $userSerializerService = $this->container->get('simplytestable.services.userserializerservice');

        $requestData = $request->request;

        if (empty($token)) {
            return $this->redirect($this->generateUrl(
                'view_user_signup_index_index',
                [],
                UrlGeneratorInterface::ABSOLUTE_URL
            ));
        }

        $invite = $teamInviteService->getForToken($token);

        if (empty($invite)) {
            return $this->redirect($this->generateUrl(
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

            return $this->redirect($this->generateUrl(
                'view_user_signup_invite_index',
                [
                    'token' => $token
                ],
                UrlGeneratorInterface::ABSOLUTE_URL
            ));
        }

        $acceptAndActivateReturnValue = $userService->activateAndAccept($invite, $password);

        if ($acceptAndActivateReturnValue !== true) {
            $session->getFlashBag()->set(
                self::FLASH_BAG_INVITE_ACCEPT_ERROR_KEY,
                self::FLASH_BAG_INVITE_ACCEPT_ERROR_MESSAGE_FAILURE
            );

            $session->getFlashBag()->set(
                self::FLASH_BAG_INVITE_ACCEPT_FAILURE_KEY,
                $acceptAndActivateReturnValue
            );

            return $this->redirect($this->generateUrl(
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
        $userService->setUser($user);

        $staySignedIn = !empty(trim($requestData->get('stay-signed-in')));

        $response = $this->redirect($this->generateUrl(
            'view_dashboard_index_index',
            [],
            UrlGeneratorInterface::ABSOLUTE_URL
        ));

        if ($staySignedIn) {
            $serializedUser = $userSerializerService->serializeToString($user);

            $cookie = new Cookie(
                'simplytestable-user',
                $serializedUser,
                time() + self::ONE_YEAR_IN_SECONDS,
                '/',
                '.simplytestable.com',
                false,
                true
            );

            $response->headers->setCookie($cookie);
        }

        return $response;
    }
}
