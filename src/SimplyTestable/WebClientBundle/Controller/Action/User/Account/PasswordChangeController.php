<?php

namespace SimplyTestable\WebClientBundle\Controller\Action\User\Account;

use SimplyTestable\WebClientBundle\Exception\CoreApplicationReadOnlyException;
use SimplyTestable\WebClientBundle\Exception\CoreApplicationRequestException;
use SimplyTestable\WebClientBundle\Exception\InvalidAdminCredentialsException;
use SimplyTestable\WebClientBundle\Exception\InvalidContentTypeException;
use SimplyTestable\WebClientBundle\Exception\InvalidCredentialsException;
use SimplyTestable\WebClientBundle\Services\UserManager;
use SimplyTestable\WebClientBundle\Services\UserService;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
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

    /**
     * @param UserService $userService
     * @param UserManager $userManager
     * @param RouterInterface $router
     * @param SessionInterface $session
     */
    public function __construct(
        UserService $userService,
        UserManager $userManager,
        RouterInterface $router,
        SessionInterface $session
    ) {
        parent::__construct($userManager, $router, $session);

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
        if ($this->hasResponse()) {
            return $this->response;
        }

        $requestData = $request->request;

        $currentPassword = trim($requestData->get('current-password'));
        $newPassword = trim($requestData->get('new-password'));

        $redirectResponse = new RedirectResponse($this->router->generate(
            'view_user_account_index_index',
            [],
            UrlGeneratorInterface::ABSOLUTE_URL
        ));

        if (empty($currentPassword) || empty($newPassword)) {
            $this->session->getFlashBag()->set(
                self::FLASH_BAG_REQUEST_KEY,
                self::FLASH_BAG_REQUEST_ERROR_MESSAGE_PASSWORD_MISSING
            );

            return $redirectResponse;
        }

        $user = $this->userManager->getUser();

        if ($currentPassword != $user->getPassword()) {
            $this->session->getFlashBag()->set(
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

            $this->session->getFlashBag()->set(
                self::FLASH_BAG_REQUEST_KEY,
                self::FLASH_BAG_REQUEST_SUCCESS_MESSAGE
            );
        } catch (CoreApplicationReadOnlyException $coreApplicationReadOnlyException) {
            $this->session->getFlashBag()->set(
                self::FLASH_BAG_REQUEST_KEY,
                self::FLASH_BAG_REQUEST_ERROR_MESSAGE_FAILED_READ_ONLY
            );
        } catch (CoreApplicationRequestException $coreApplicationRequestException) {
            $this->session->getFlashBag()->set(
                self::FLASH_BAG_REQUEST_KEY,
                self::FLASH_BAG_REQUEST_ERROR_MESSAGE_FAILED_UNKNOWN
            );
        }

        return $redirectResponse;
    }
}
