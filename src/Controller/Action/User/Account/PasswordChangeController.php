<?php

namespace App\Controller\Action\User\Account;

use App\Exception\CoreApplicationReadOnlyException;
use App\Exception\CoreApplicationRequestException;
use App\Exception\InvalidAdminCredentialsException;
use App\Exception\InvalidContentTypeException;
use App\Exception\InvalidCredentialsException;
use App\Services\UserManager;
use App\Services\UserService;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Flash\FlashBagInterface;
use Symfony\Component\Routing\RouterInterface;

class PasswordChangeController extends AbstractUserAccountController
{
    const FLASH_BAG_REQUEST_KEY = 'user_account_details_update_password_request_notice';
    const FLASH_BAG_REQUEST_ERROR_MESSAGE_PASSWORD_MISSING = 'password-missing';
    const FLASH_BAG_REQUEST_ERROR_MESSAGE_PASSWORD_INVALID = 'password-invalid';
    const FLASH_BAG_REQUEST_ERROR_MESSAGE_FAILED_READ_ONLY = 'password-failed-read-only';
    const FLASH_BAG_REQUEST_ERROR_MESSAGE_FAILED_UNKNOWN = 'unknown';
    const FLASH_BAG_REQUEST_SUCCESS_MESSAGE = 'password-done';

    /**
     * @var UserService
     */
    private $userService;

    public function __construct(
        RouterInterface $router,
        UserManager $userManager,
        FlashBagInterface $flashBag,
        UserService $userService
    ) {
        parent::__construct($router, $userManager, $flashBag);

        $this->userService = $userService;
    }

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
        $requestData = $request->request;

        $currentPassword = trim($requestData->get('current-password'));
        $newPassword = trim($requestData->get('new-password'));

        $redirectResponse = $this->createUserAccountRedirectResponse();

        if (empty($currentPassword) || empty($newPassword)) {
            $this->flashBag->set(
                self::FLASH_BAG_REQUEST_KEY,
                self::FLASH_BAG_REQUEST_ERROR_MESSAGE_PASSWORD_MISSING
            );

            return $redirectResponse;
        }

        $user = $this->userManager->getUser();

        if ($currentPassword != $user->getPassword()) {
            $this->flashBag->set(
                self::FLASH_BAG_REQUEST_KEY,
                self::FLASH_BAG_REQUEST_ERROR_MESSAGE_PASSWORD_INVALID
            );

            return $redirectResponse;
        }

        $token = $this->userService->getConfirmationToken($user->getUsername());

        try {
            $this->userService->resetPassword($token, $newPassword);

            $user->setPassword($newPassword);
            $this->userManager->setUser($user);

            $cookieUser = $request->cookies->get(UserManager::USER_COOKIE_KEY);

            if (!empty($cookieUser)) {
                $redirectResponse->headers->setCookie($this->userManager->createUserCookie());
            }

            $this->flashBag->set(
                self::FLASH_BAG_REQUEST_KEY,
                self::FLASH_BAG_REQUEST_SUCCESS_MESSAGE
            );
        } catch (CoreApplicationReadOnlyException $coreApplicationReadOnlyException) {
            $this->flashBag->set(
                self::FLASH_BAG_REQUEST_KEY,
                self::FLASH_BAG_REQUEST_ERROR_MESSAGE_FAILED_READ_ONLY
            );
        } catch (CoreApplicationRequestException $coreApplicationRequestException) {
            $this->flashBag->set(
                self::FLASH_BAG_REQUEST_KEY,
                self::FLASH_BAG_REQUEST_ERROR_MESSAGE_FAILED_UNKNOWN
            );
        }

        return $redirectResponse;
    }
}
