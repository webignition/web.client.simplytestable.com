<?php

namespace SimplyTestable\WebClientBundle\Controller\Action\User\Account;

use SimplyTestable\WebClientBundle\Services\UserService;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class PasswordChangeController extends AccountCredentialsChangeController
{
    const FLASH_BAG_REQUEST_KEY = 'user_account_details_update_password_request_notice';
    const FLASH_BAG_REQUEST_ERROR_MESSAGE_PASSWORD_MISSING = 'password-missing';
    const FLASH_BAG_REQUEST_ERROR_MESSAGE_PASSWORD_INVALID = 'password-invalid';
    const FLASH_BAG_REQUEST_ERROR_MESSAGE_FAILED_READ_ONLY = 'password-failed-read-only';
    const FLASH_BAG_REQUEST_ERROR_MESSAGE_FAILED_UNKNOWN = 'unknown';
    const FLASH_BAG_REQUEST_SUCCESS_MESSAGE = 'password-done';

    /**
     * @param Request $request
     *
     * @return RedirectResponse
     */
    public function requestAction(Request $request)
    {
        $session = $this->container->get('session');
        $userService = $this->container->get('simplytestable.services.userservice');
        $userSerializerService = $this->container->get('simplytestable.services.userserializerservice');

        $requestData = $request->request;

        $currentPassword = strtolower(trim($requestData->get('current-password')));
        $newPassword = strtolower(trim($requestData->get('new-password')));

        $redirectResponse = $this->redirect($this->generateUrl(
            'view_user_account_index_index',
            [],
            UrlGeneratorInterface::ABSOLUTE_URL
        ));

        if (empty($currentPassword) || empty($newPassword)) {
            $session->getFlashBag()->set(
                self::FLASH_BAG_REQUEST_KEY,
                self::FLASH_BAG_REQUEST_ERROR_MESSAGE_PASSWORD_MISSING
            );

            return $redirectResponse;
        }

        $user = $userService->getUser();

        if ($currentPassword != $user->getPassword()) {
            $session->getFlashBag()->set(
                self::FLASH_BAG_REQUEST_KEY,
                self::FLASH_BAG_REQUEST_ERROR_MESSAGE_PASSWORD_INVALID
            );

            return $redirectResponse;
        }

        $userService->setUser($user);
        $passwordResetResponse = $userService->resetLoggedInUserPassword($newPassword);

        if ($passwordResetResponse === true) {
            $user->setPassword($newPassword);
            $userService->setUser($user);

            $cookieSerializedUser = $request->cookies->get(UserService::USER_COOKIE_KEY);

            if (!empty($cookieSerializedUser)) {
                $serializedUser = $userSerializerService->serializeToString($user);
                $userCookie = $this->createUserAuthenticationCookie($serializedUser);

                $redirectResponse->headers->setCookie($userCookie);
            }

            $session->getFlashBag()->set(
                self::FLASH_BAG_REQUEST_KEY,
                self::FLASH_BAG_REQUEST_SUCCESS_MESSAGE
            );

        } else {
            if ($passwordResetResponse === 503) {
                $session->getFlashBag()->set(
                    self::FLASH_BAG_REQUEST_KEY,
                    self::FLASH_BAG_REQUEST_ERROR_MESSAGE_FAILED_READ_ONLY
                );
            } else {
                $session->getFlashBag()->set(
                    self::FLASH_BAG_REQUEST_KEY,
                    self::FLASH_BAG_REQUEST_ERROR_MESSAGE_FAILED_UNKNOWN
                );
            }
        }

        return $redirectResponse;
    }
}
