<?php

namespace SimplyTestable\WebClientBundle\Services;

use SimplyTestable\WebClientBundle\Exception\CoreApplicationReadOnlyException;
use SimplyTestable\WebClientBundle\Exception\CoreApplicationRequestException;
use SimplyTestable\WebClientBundle\Exception\InvalidAdminCredentialsException;
use SimplyTestable\WebClientBundle\Exception\InvalidContentTypeException;
use SimplyTestable\WebClientBundle\Exception\InvalidCredentialsException;
use SimplyTestable\WebClientBundle\Exception\UserEmailChangeException;

class UserEmailChangeRequestService
{
    const EXCEPTION_CODE_EMAIL_TAKEN = 409;

    /**
     * @var CoreApplicationHttpClient
     */
    private $coreApplicationHttpClient;

    /**
     * @param CoreApplicationHttpClient $coreApplicationHttpClient
     */
    public function __construct(CoreApplicationHttpClient $coreApplicationHttpClient)
    {
        $this->coreApplicationHttpClient = $coreApplicationHttpClient;
    }

    /**
     * @param $email
     * @return array
     *
     * @throws InvalidAdminCredentialsException
     * @throws InvalidCredentialsException
     * @throws CoreApplicationReadOnlyException
     * @throws CoreApplicationRequestException
     * @throws InvalidContentTypeException
     */
    public function getEmailChangeRequest($email)
    {
        return $this->coreApplicationHttpClient->getJsonData(
            'user_email_change_request_get',
            [
                'email' => $email
            ],
            [
                CoreApplicationHttpClient::OPT_AS_ADMIN => true,
                CoreApplicationHttpClient::OPT_TREAT_404_AS_EMPTY => true,
            ]
        );
    }

    /**
     * @throws InvalidAdminCredentialsException
     * @throws InvalidCredentialsException
     */
    public function cancelEmailChangeRequest()
    {
        try {
            $this->coreApplicationHttpClient->post('user_email_change_request_cancel', [
                'email' => CoreApplicationHttpClient::ROUTE_PARAMETER_USER_PLACEHOLDER,
            ]);
        } catch (CoreApplicationReadOnlyException $coreApplicationReadOnlyException) {
        } catch (CoreApplicationRequestException $coreApplicationRequestException) {
        }
    }

    /**
     * @param array $emailChangeRequest
     *
     * @throws InvalidAdminCredentialsException
     * @throws InvalidCredentialsException
     * @throws UserEmailChangeException
     */
    public function confirmEmailChangeRequest(array $emailChangeRequest)
    {
        try {
            $this->coreApplicationHttpClient->post('user_email_change_request_confirm', [
                'email' => CoreApplicationHttpClient::ROUTE_PARAMETER_USER_PLACEHOLDER,
                'token' => $emailChangeRequest['token'],
            ]);
        } catch (CoreApplicationReadOnlyException $coreApplicationReadOnlyException) {
        } catch (CoreApplicationRequestException $coreApplicationRequestException) {
            if ($coreApplicationRequestException->isHttpException()) {
                if (self::EXCEPTION_CODE_EMAIL_TAKEN === $coreApplicationRequestException->getCode()) {
                    throw new UserEmailChangeException(
                        sprintf(
                            UserEmailChangeException::MESSAGE_EMAIL_ALREADY_TAKEN,
                            $emailChangeRequest['new_email']
                        ),
                        UserEmailChangeException::CODE_EMAIL_ALREADY_TAKEN,
                        $coreApplicationRequestException
                    );
                }

                throw new UserEmailChangeException(
                    UserEmailChangeException::MESSAGE_UNKNOWN,
                    UserEmailChangeException::CODE_UNKNOWN,
                    $coreApplicationRequestException
                );
            }
        }
    }

    /**
     * @param string $newEmail
     *
     * @throws InvalidAdminCredentialsException
     * @throws InvalidCredentialsException
     * @throws UserEmailChangeException
     */
    public function createEmailChangeRequest($newEmail)
    {
        try {
            $this->coreApplicationHttpClient->post('user_email_change_request_create', [
                'email' => CoreApplicationHttpClient::ROUTE_PARAMETER_USER_PLACEHOLDER,
                'new_email' => $newEmail,
            ]);
        } catch (CoreApplicationReadOnlyException $coreApplicationReadOnlyException) {
        } catch (CoreApplicationRequestException $coreApplicationRequestException) {
            if ($coreApplicationRequestException->isHttpException()) {
                if (self::EXCEPTION_CODE_EMAIL_TAKEN === $coreApplicationRequestException->getCode()) {
                    throw new UserEmailChangeException(
                        sprintf(
                            UserEmailChangeException::MESSAGE_EMAIL_ALREADY_TAKEN,
                            $newEmail
                        ),
                        UserEmailChangeException::CODE_EMAIL_ALREADY_TAKEN,
                        $coreApplicationRequestException
                    );
                }

                throw new UserEmailChangeException(
                    UserEmailChangeException::MESSAGE_UNKNOWN,
                    UserEmailChangeException::CODE_UNKNOWN,
                    $coreApplicationRequestException
                );
            }
        }
    }
}
