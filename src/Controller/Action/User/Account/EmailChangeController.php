<?php

namespace App\Controller\Action\User\Account;

use App\Services\Mailer;
use Egulias\EmailValidator\EmailValidator;
use Egulias\EmailValidator\Validation\RFCValidation;
use Postmark\Models\PostmarkException;
use App\Exception\CoreApplicationRequestException;
use App\Exception\InvalidAdminCredentialsException;
use App\Exception\InvalidContentTypeException;
use App\Exception\InvalidCredentialsException;
use App\Exception\UserEmailChangeException;
use App\Resque\Job\EmailListSubscribeJob;
use App\Resque\Job\EmailListUnsubscribeJob;
use App\Services\ResqueQueueService;
use App\Services\UserEmailChangeRequestService;
use App\Services\UserManager;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Flash\FlashBagInterface;
use App\Exception\Mail\Configuration\Exception as MailConfigurationException;
use Symfony\Component\Routing\RouterInterface;

class EmailChangeController extends AbstractUserAccountController
{
    const FLASH_BAG_CONFIRM_KEY = 'user_account_details_update_email_confirm_notice';
    const FLASH_BAG_CONFIRM_ERROR_MESSAGE_TOKEN_INVALID = 'invalid-token';
    const FLASH_BAG_CONFIRM_ERROR_MESSAGE_EMAIL_TAKEN = 'email-taken';
    const FLASH_BAG_CONFIRM_ERROR_MESSAGE_UNKNOWN = 'unknown';
    const FLASH_BAG_CONFIRM_MESSAGE_SUCCESS = 'success';

    const FLASH_BAG_EMAIL_VALUE_KEY = 'user_account_details_update_email';

    const FLASH_BAG_REQUEST_KEY = 'user_account_details_update_email_request_notice';
    const FLASH_BAG_REQUEST_ERROR_MESSAGE_EMAIL_EMPTY = 'blank-email';
    const FLASH_BAG_REQUEST_ERROR_MESSAGE_EMAIL_INVALID = 'invalid-email';
    const FLASH_BAG_REQUEST_MESSAGE_EMAIL_SAME = 'same-email';
    const FLASH_BAG_REQUEST_ERROR_MESSAGE_EMAIL_TAKEN = 'email-taken';
    const FLASH_BAG_REQUEST_ERROR_MESSAGE_UNKNOWN = 'unknown';

    const FLASH_BAG_REQUEST_MESSAGE_SUCCESS = 'email-done';

    const FLASH_BAG_RESEND_SUCCESS_KEY = 'user_account_details_resend_email_change_notice';
    const FLASH_BAG_RESEND_ERROR_KEY = 'user_account_details_resend_email_change_error';
    const FLASH_BAG_RESEND_MESSAGE_SUCCESS = 're-sent';
    const FLASH_BAG_RESEND_ERROR_MESSAGE_EMAIL_INVALID = 'invalid-email';

    const FLASH_BAG_ERROR_MESSAGE_POSTMARK_NOT_ALLOWED_TO_SEND = 'postmark-not-allowed-to-send';
    const FLASH_BAG_ERROR_MESSAGE_POSTMARK_INACTIVE_RECIPIENT = 'postmark-inactive-recipient';
    const FLASH_BAG_ERROR_MESSAGE_POSTMARK_UNKNOWN = 'postmark-failure';

    /**
     * @var UserEmailChangeRequestService
     */
    private $emailChangeRequestService;

    public function __construct(
        RouterInterface $router,
        UserManager $userManager,
        FlashBagInterface $flashBag,
        UserEmailChangeRequestService $emailChangeRequestService
    ) {
        parent::__construct($router, $userManager, $flashBag);

        $this->emailChangeRequestService = $emailChangeRequestService;
    }

    /**
     * @param Mailer $mailer
     * @param Request $request
     *
     * @return RedirectResponse
     *
     * @throws CoreApplicationRequestException
     * @throws InvalidAdminCredentialsException
     * @throws InvalidContentTypeException
     * @throws InvalidCredentialsException
     * @throws MailConfigurationException
     */
    public function requestAction(Mailer $mailer, Request $request)
    {
        $requestData = $request->request;

        $redirectResponse = $this->createUserAccountRedirectResponse();

        $user = $this->userManager->getUser();
        $username = $user->getUsername();

        $newEmail = strtolower(trim($requestData->get('email')));

        if (empty($newEmail)) {
            $this->flashBag->set(
                self::FLASH_BAG_REQUEST_KEY,
                self::FLASH_BAG_REQUEST_ERROR_MESSAGE_EMAIL_EMPTY
            );

            return $redirectResponse;
        }

        if ($newEmail === $username) {
            $this->flashBag->set(
                self::FLASH_BAG_REQUEST_KEY,
                self::FLASH_BAG_REQUEST_MESSAGE_EMAIL_SAME
            );

            return $redirectResponse;
        }

        $emailValidator = new EmailValidator;
        if (!$emailValidator->isValid($newEmail, new RFCValidation())) {
            $this->flashBag->set(
                self::FLASH_BAG_REQUEST_KEY,
                self::FLASH_BAG_REQUEST_ERROR_MESSAGE_EMAIL_INVALID
            );

            $this->flashBag->set(
                self::FLASH_BAG_EMAIL_VALUE_KEY,
                $newEmail
            );

            return $redirectResponse;
        }

        try {
            $this->emailChangeRequestService->createEmailChangeRequest($newEmail);
            $emailChangeRequest = $this->emailChangeRequestService->getEmailChangeRequest($username);
            $mailer->sendEmailChangeConfirmationToken($newEmail, $username, $emailChangeRequest['token']);

            $this->flashBag->set(
                self::FLASH_BAG_REQUEST_KEY,
                self::FLASH_BAG_REQUEST_MESSAGE_SUCCESS
            );
        } catch (UserEmailChangeException $userEmailChangeException) {
            if ($userEmailChangeException->isEmailAddressAlreadyTakenException()) {
                $flashMessage = self::FLASH_BAG_REQUEST_ERROR_MESSAGE_EMAIL_TAKEN;
            } else {
                $flashMessage = self::FLASH_BAG_REQUEST_ERROR_MESSAGE_UNKNOWN;
            }

            $this->flashBag->set(
                self::FLASH_BAG_REQUEST_KEY,
                $flashMessage
            );

            $this->flashBag->set(
                self::FLASH_BAG_EMAIL_VALUE_KEY,
                $newEmail
            );
        } catch (PostmarkException $postmarkException) {
            $this->emailChangeRequestService->cancelEmailChangeRequest();

            if (405 === $postmarkException->postmarkApiErrorCode) {
                $flashMessage = self::FLASH_BAG_ERROR_MESSAGE_POSTMARK_NOT_ALLOWED_TO_SEND;
            } elseif (406 === $postmarkException->postmarkApiErrorCode) {
                $flashMessage = self::FLASH_BAG_ERROR_MESSAGE_POSTMARK_INACTIVE_RECIPIENT;
            } elseif (300 === $postmarkException->postmarkApiErrorCode) {
                $flashMessage = self::FLASH_BAG_REQUEST_ERROR_MESSAGE_EMAIL_INVALID;
            } else {
                $flashMessage = self::FLASH_BAG_ERROR_MESSAGE_POSTMARK_UNKNOWN;
            }

            $this->flashBag->set(self::FLASH_BAG_REQUEST_KEY, $flashMessage);

            $this->flashBag->set(
                self::FLASH_BAG_EMAIL_VALUE_KEY,
                $newEmail
            );
        }

        return $redirectResponse;
    }

    /**
     * @param Mailer $mailer
     * @param UserManager $userManager
     *
     * @return RedirectResponse
     *
     * @throws CoreApplicationRequestException
     * @throws InvalidAdminCredentialsException
     * @throws InvalidContentTypeException
     * @throws MailConfigurationException
     */
    public function resendAction(Mailer $mailer, UserManager $userManager)
    {
        $user = $userManager->getUser();
        $username = $user->getUsername();

        try {
            $emailChangeRequest = $this->emailChangeRequestService->getEmailChangeRequest($username);
            $mailer->sendEmailChangeConfirmationToken(
                $emailChangeRequest['new_email'],
                $username,
                $emailChangeRequest['token']
            );
            $this->flashBag->set(
                self::FLASH_BAG_RESEND_SUCCESS_KEY,
                self::FLASH_BAG_RESEND_MESSAGE_SUCCESS
            );
        } catch (PostmarkException $postmarkException) {
            if (405 === $postmarkException->postmarkApiErrorCode) {
                $this->flashBag->set(
                    self::FLASH_BAG_RESEND_ERROR_KEY,
                    self::FLASH_BAG_ERROR_MESSAGE_POSTMARK_NOT_ALLOWED_TO_SEND
                );
            } elseif (406 === $postmarkException->postmarkApiErrorCode) {
                $this->flashBag->set(
                    self::FLASH_BAG_RESEND_ERROR_KEY,
                    self::FLASH_BAG_ERROR_MESSAGE_POSTMARK_INACTIVE_RECIPIENT
                );
            } elseif (300 === $postmarkException->postmarkApiErrorCode) {
                $this->flashBag->set(
                    self::FLASH_BAG_RESEND_ERROR_KEY,
                    self::FLASH_BAG_RESEND_ERROR_MESSAGE_EMAIL_INVALID
                );
            } else {
                $this->flashBag->set(
                    self::FLASH_BAG_RESEND_ERROR_KEY,
                    self::FLASH_BAG_ERROR_MESSAGE_POSTMARK_UNKNOWN
                );
            }
        }

        return $this->createUserAccountRedirectResponse();
    }

    /**
     * @param ResqueQueueService $resqueQueueService
     * @param Request $request
     * @return RedirectResponse
     *
     * @throws CoreApplicationRequestException
     * @throws InvalidAdminCredentialsException
     * @throws InvalidContentTypeException
     * @throws InvalidCredentialsException
     * @throws \CredisException
     */
    public function confirmAction(
        ResqueQueueService $resqueQueueService,
        Request $request
    ) {
        $requestData = $request->request;

        $redirectResponse = $this->createUserAccountRedirectResponse();

        $token = trim($requestData->get('token'));

        if (empty($token)) {
            $this->flashBag->set(
                self::FLASH_BAG_CONFIRM_KEY,
                self::FLASH_BAG_CONFIRM_ERROR_MESSAGE_TOKEN_INVALID
            );

            return $redirectResponse;
        }

        $user = $this->userManager->getUser();
        $username = $user->getUsername();

        $emailChangeRequest = $this->emailChangeRequestService->getEmailChangeRequest($username);
        if (empty($emailChangeRequest)) {
            return $redirectResponse;
        }

        if ($token !== $emailChangeRequest['token']) {
            $this->flashBag->set(
                self::FLASH_BAG_CONFIRM_KEY,
                self::FLASH_BAG_CONFIRM_ERROR_MESSAGE_TOKEN_INVALID
            );

            return $redirectResponse;
        }

        try {
            $this->emailChangeRequestService->confirmEmailChangeRequest($emailChangeRequest);
        } catch (UserEmailChangeException $userEmailChangeException) {
            if ($userEmailChangeException->isEmailAddressAlreadyTakenException()) {
                $this->flashBag->set(
                    self::FLASH_BAG_CONFIRM_KEY,
                    self::FLASH_BAG_CONFIRM_ERROR_MESSAGE_EMAIL_TAKEN
                );
                $this->flashBag->set(
                    self::FLASH_BAG_EMAIL_VALUE_KEY,
                    $emailChangeRequest['new_email']
                );
            } else {
                $this->flashBag->set(
                    self::FLASH_BAG_CONFIRM_KEY,
                    self::FLASH_BAG_CONFIRM_ERROR_MESSAGE_UNKNOWN
                );
            }

            return $redirectResponse;
        }

        $oldEmail = $username;
        $newEmail = $emailChangeRequest['new_email'];

        $resqueQueueService->enqueue(new EmailListSubscribeJob([
            'listId' => 'announcements',
            'email' => $newEmail,
        ]));

        $resqueQueueService->enqueue(new EmailListUnsubscribeJob([
            'listId' => 'announcements',
            'email' => $oldEmail,
        ]));

        $user->setUsername($emailChangeRequest['new_email']);
        $this->userManager->setUser($user);

        if (!is_null($request->cookies->get('simplytestable-user'))) {
            $redirectResponse->headers->setCookie($this->userManager->createUserCookie());
        }

        $this->flashBag->set(
            self::FLASH_BAG_CONFIRM_KEY,
            self::FLASH_BAG_CONFIRM_MESSAGE_SUCCESS
        );


        return $redirectResponse;
    }

    /**
     * @return RedirectResponse
     *
     * @throws InvalidCredentialsException
     */
    public function cancelAction()
    {
        $this->emailChangeRequestService->cancelEmailChangeRequest();
        $this->flashBag->set('user_account_details_cancel_email_change_notice', 'cancelled');

        return $this->createUserAccountRedirectResponse();
    }
}
