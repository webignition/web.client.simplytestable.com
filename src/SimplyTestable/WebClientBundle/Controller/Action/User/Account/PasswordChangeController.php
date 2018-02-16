<?php

namespace SimplyTestable\WebClientBundle\Controller\Action\User\Account;

use SimplyTestable\WebClientBundle\Exception\CoreApplicationReadOnlyException;
use SimplyTestable\WebClientBundle\Exception\CoreApplicationRequestException;
use SimplyTestable\WebClientBundle\Exception\InvalidAdminCredentialsException;
use SimplyTestable\WebClientBundle\Exception\InvalidContentTypeException;
use SimplyTestable\WebClientBundle\Exception\InvalidCredentialsException;
use SimplyTestable\WebClientBundle\Services\UserManager;
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
     *
     * @throws CoreApplicationRequestException
     * @throws InvalidAdminCredentialsException
     * @throws InvalidContentTypeException
     * @throws InvalidCredentialsException
     */
    public function requestAction(Request $request)
    {
        $session = $this->container->get('session');
        $userService = $this->container->get('simplytestable.services.userservice');
        $userManager = $this->container->get(UserManager::class);
        $router = $this->container->get('router');

        $requestData = $request->request;

        $currentPassword = trim($requestData->get('current-password'));
        $newPassword = trim($requestData->get('new-password'));

        $redirectResponse = new RedirectResponse($router->generate(
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

        $user = $userManager->getUser();

        if ($currentPassword != $user->getPassword()) {
            $session->getFlashBag()->set(
                self::FLASH_BAG_REQUEST_KEY,
                self::FLASH_BAG_REQUEST_ERROR_MESSAGE_PASSWORD_INVALID
            );

            return $redirectResponse;
        }

        $token = $userService->getConfirmationToken($user->getUsername());

        try {
            $userService->resetPassword($token, $newPassword);

            $user->setPassword($newPassword);
            $userManager->setUser($user);

            $cookieUser = $request->cookies->get(UserManager::USER_COOKIE_KEY);

            if (!empty($cookieUser)) {
                $redirectResponse->headers->setCookie($userManager->createUserCookie());
            }

            $session->getFlashBag()->set(
                self::FLASH_BAG_REQUEST_KEY,
                self::FLASH_BAG_REQUEST_SUCCESS_MESSAGE
            );
        } catch (CoreApplicationReadOnlyException $coreApplicationReadOnlyException) {
            $session->getFlashBag()->set(
                self::FLASH_BAG_REQUEST_KEY,
                self::FLASH_BAG_REQUEST_ERROR_MESSAGE_FAILED_READ_ONLY
            );
        } catch (CoreApplicationRequestException $coreApplicationRequestException) {
            $session->getFlashBag()->set(
                self::FLASH_BAG_REQUEST_KEY,
                self::FLASH_BAG_REQUEST_ERROR_MESSAGE_FAILED_UNKNOWN
            );
        }

        return $redirectResponse;
    }
}
