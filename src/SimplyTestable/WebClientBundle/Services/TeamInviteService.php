<?php
namespace SimplyTestable\WebClientBundle\Services;

use Guzzle\Http\Exception\BadResponseException;
use Guzzle\Http\Message\Request;
use Guzzle\Http\Message\Response;
use SimplyTestable\WebClientBundle\Model\Team\Invite;
use SimplyTestable\WebClientBundle\Exception\Team\Service\Exception as TeamServiceException;
use SimplyTestable\WebClientBundle\Exception\WebResourceException;
use SimplyTestable\WebClientBundle\Exception\CoreApplicationAdminRequestException;
use webignition\WebResource\JsonDocument\JsonDocument;

class TeamInviteService extends CoreApplicationService
{
    /**
     * @var UserService
     */
    private $userService;

    /**
     * @var Invite[]
     */
    private $inviteCache = [];

    /**
     * @var HttpClientService
     */
    private $httpClientService;

    /**
     * @param array $parameters
     * @param WebResourceService $webResourceService
     * @param UserService $userService
     */
    public function __construct(
        $parameters,
        WebResourceService $webResourceService,
        UserService $userService
    ) {
        parent::__construct($parameters, $webResourceService);
        $this->userService = $userService;
        $this->httpClientService = $webResourceService->getHttpClientService();
    }

    /**
     * @param $inviteeEmail
     * @return Invite
     *
     * @throws TeamServiceException
     * @throws WebResourceException
     */
    public function get($inviteeEmail)
    {
        $request = $this->httpClientService->getRequest(
            $this->getUrl('teaminvite_get', [
                'invitee_email' => $inviteeEmail
            ])
        );

        $this->addAuthorisationToRequest($request);

        try {
            /* @var JsonDocument $jsonDocument */
            $jsonDocument = $this->webResourceService->get($request);

            return new Invite($jsonDocument->getContentObject());
        } catch (WebResourceException $webResourceException) {
            $response = $webResourceException->getResponse();

            if ($response->getStatusCode() == 400 && $response->hasHeader('X-TeamInviteGet-Error-Code')) {
                throw new TeamServiceException(
                    (string)$response->getHeader('X-TeamInviteGet-Error-Message'),
                    (int)(string)$response->getHeader('X-TeamInviteGet-Error-Code')
                );
            }

            throw $webResourceException;
        }
    }

    /**
     * @return Invite[]
     *
     * @throws WebResourceException
     */
    public function getForUser()
    {
        $request = $this->httpClientService->getRequest(
            $this->getUrl('teaminvite_userlist')
        );

        $this->addAuthorisationToRequest($request);

        /* @var JsonDocument $jsonDocument */
        $jsonDocument = $this->webResourceService->get($request);
        $inviteData = $jsonDocument->getContentObject();
        $invites = [];

        foreach ($inviteData as $rawInvite) {
            $invites[] = new Invite($rawInvite);
        }

        return $invites;
    }

    /**
     * @param Invite $invite
     *
     * @return bool
     */
    public function declineInvite(Invite $invite)
    {
        $request = $this->httpClientService->postRequest(
            $this->getUrl('teaminvite_decline'),
            null,
            [
                'team' => $invite->getTeam()
            ]
        );

        $this->addAuthorisationToRequest($request);

        try {
            $this->webResourceService->get($request);
        } catch (WebResourceException $webResourceException) {
            return false;
        }

        return true;
    }

    /**
     * @param Invite $invite
     *
     * @return bool
     */
    public function acceptInvite(Invite $invite)
    {
        $request = $this->httpClientService->postRequest(
            $this->getUrl('teaminvite_accept'),
            null,
            [
                'team' => $invite->getTeam()
            ]
        );

        $this->addAuthorisationToRequest($request);

        try {
            $this->webResourceService->get($request);
        } catch (WebResourceException $webResourceException) {
            return false;
        }

        return true;
    }

    /**
     * @return Invite[]
     *
     * @throws WebResourceException
     */
    public function getForTeam()
    {
        $request = $this->httpClientService->getRequest(
            $this->getUrl('team_invites')
        );

        $this->addAuthorisationToRequest($request);

        /* @var JsonDocument $jsonDocument */
        $jsonDocument = $this->webResourceService->get($request);
        $inviteData = $jsonDocument->getContentObject();
        $invites = [];

        foreach ($inviteData as $rawInvite) {
            $invites[] = new Invite($rawInvite);
        }

        return $invites;
    }

    /**
     * @param Invite $invite
     *
     * @return bool
     */
    public function removeForUser(Invite $invite)
    {
        $request = $this->httpClientService->postRequest(
            $this->getUrl('teaminvite_remove', [
                'invitee_email' => $invite->getUser()
            ])
        );

        $this->addAuthorisationToRequest($request);

        try {
            $this->webResourceService->get($request);
        } catch (WebResourceException $webResourceException) {
            return false;
        }

        return true;
    }

    /**
     * @param string $token
     *
     * @return Invite
     *
     * @throws CoreApplicationAdminRequestException
     */
    public function getForToken($token)
    {
        if (!isset($this->inviteCache[$token])) {
            $requestUrl = $this->getUrl('teaminvite_getbytoken', [
                'token' => $token
            ]);

            $decodedResponse = json_decode(
                $this->getAdminResponse($this->httpClientService->getRequest($requestUrl))->getBody()
            );

            if ($decodedResponse instanceof \stdClass) {
                $this->inviteCache[$token] = new Invite($decodedResponse);
            } else {
                $this->inviteCache[$token] = null;
            }
        }

        return $this->inviteCache[$token];
    }

    /**
     * @param Request $request
     *
     * @return bool|Response
     *
     * @throws CoreApplicationAdminRequestException
     */
    private function getAdminResponse(Request $request)
    {
        $currentUser = $this->getUser();

        $this->setUser($this->userService->getAdminUser());
        $this->addAuthorisationToRequest($request);

        try {
            $response = $request->send();
        } catch (BadResponseException $badResponseException) {
            $response = $badResponseException->getResponse();
        }

        if (!is_null($currentUser)) {
            $this->setUser($currentUser);
        }

        if ($response->getStatusCode() == 401) {
            throw new CoreApplicationAdminRequestException('Invalid admin user credentials', 401);
        }

        if (is_null($response)) {
            return null;
        }

        return $response;
    }
}
