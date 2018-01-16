<?php
namespace SimplyTestable\WebClientBundle\Services;

use Guzzle\Http\Exception\BadResponseException;
use Guzzle\Http\Exception\CurlException;
use Guzzle\Http\Message\Request;
use SimplyTestable\WebClientBundle\Exception\CoreApplicationAdminRequestException;

class UserEmailChangeRequestService extends UserService
{
    /**
     * @var array
     */
    private $emailChangeRequestCache = [];

    /**
     * @param string $email
     *
     * @return bool
     *
     * @throws CoreApplicationAdminRequestException
     */
    public function hasEmailChangeRequest($email)
    {
        return !is_null($this->getEmailChangeRequest($email));
    }

    /**
     * @param string $email
     *
     * @return array
     *
     * @throws CoreApplicationAdminRequestException
     */
    public function getEmailChangeRequest($email)
    {
        if (!isset($this->emailChangeRequestCache[$email])) {
            $requestUrl = $this->getUrl('user_email_change_request_get', [
                'email' => $email
            ]);

            $request = $this->webResourceService->getHttpClientService()->getRequest($requestUrl);
            $response = $this->getAdminResponse($request);

            if ($response->getStatusCode() === 200) {
                $this->emailChangeRequestCache[$email] = json_decode($response->getBody(), true);
            } else {
                return null;
            }
        }

        return $this->emailChangeRequestCache[$email];
    }

    /**
     * @return bool|int|null
     */
    public function cancelEmailChangeRequest()
    {
        $request = $this->webResourceService->getHttpClientService()->postRequest(
            $this->getUrl('user_email_change_request_cancel', [
                'email' => $this->getUser()->getUsername()
            ])
        );

        return $this->issueUserRequest($request);
    }

    /**
     * @param string $token
     *
     * @return bool|int|null
     */
    public function confirmEmailChangeRequest($token)
    {
        $request = $this->webResourceService->getHttpClientService()->postRequest(
            $this->getUrl('user_email_change_request_confirm', array(
                'email' => $this->getUser()->getUsername(),
                'token' => $token
            ))
        );

        return $this->issueUserRequest($request);
    }

    /**
     * @param string $newEmail
     *
     * @return bool|int|null
     */
    public function createEmailChangeRequest($newEmail)
    {
        $request = $this->webResourceService->getHttpClientService()->postRequest(
            $this->getUrl('user_email_change_request_create', array(
                'email' => $this->getUser()->getUsername(),
                'new_email' => $newEmail
            ))
        );

        return $this->issueUserRequest($request);
    }

    /**
     * @param Request $request
     *
     * @return bool|int|null
     */
    private function issueUserRequest(Request $request)
    {
        $this->addAuthorisationToRequest($request);

        try {
            $response = $request->send();

            return $response->getStatusCode() == 200 ? true : $response->getStatusCode();
        } catch (BadResponseException $badResponseException) {
            return $badResponseException->getResponse()->getStatusCode();
        } catch (CurlException $curlException) {
            return $curlException->getErrorNo();
        }
    }
}
