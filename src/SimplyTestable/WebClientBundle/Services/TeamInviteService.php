<?php
namespace SimplyTestable\WebClientBundle\Services;

use SimplyTestable\WebClientBundle\Model\Team\Invite;
use SimplyTestable\WebClientBundle\Exception\Team\Service\Exception as TeamServiceException;
use SimplyTestable\WebClientBundle\Exception\WebResourceException;
use SimplyTestable\WebClientBundle\Services\UserService;
use SimplyTestable\WebClientBundle\Exception\CoreApplicationAdminRequestException;

class TeamInviteService extends CoreApplicationService {

    /**
     *
     * @var UserService
     */
    private $userService;


    /**
     * @var Invite[]
     */
    private $inviteCache = [];


    public function __construct(
        $parameters,
        WebResourceService $webResourceService,
        UserService $userService
    ) {
        parent::__construct($parameters, $webResourceService);
        $this->userService = $userService;
    }


    /**
     * @param $inviteeEmail
     * @return Invite
     * @throws \SimplyTestable\WebClientBundle\Exception\Team\Service\Exception
     * @throws \SimplyTestable\WebClientBundle\Exception\WebResourceException
     */
    public function get($inviteeEmail) {
        $request = $this->webResourceService->getHttpClientService()->getRequest(
            $this->getUrl('teaminvite_get', [
                'invitee_email' => $inviteeEmail
            ])
        );

        $this->addAuthorisationToRequest($request);

        try {
            return new Invite($this->webResourceService->get($request)->getContentObject());
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
     */
    public function getForUser() {
        $request = $this->webResourceService->getHttpClientService()->getRequest(
            $this->getUrl('teaminvite_userlist')
        );

        $this->addAuthorisationToRequest($request);

        $inviteData = $this->webResourceService->get($request)->getContentObject();
        $invites = [];

        foreach ($inviteData as $rawInvite) {
            $invites[] = new Invite($rawInvite);
        }

        return $invites;
    }


    public function declineInvite(Invite $invite) {
        $request = $this->webResourceService->getHttpClientService()->postRequest(
            $this->getUrl('teaminvite_decline'),
            null,
            [
                'team' => $invite->getTeam()
            ]
        );

        $this->addAuthorisationToRequest($request);

        try {
            $this->webResourceService->get($request);
            return true;
        } catch (WebResourceException $webResourceException) {
            return false;
        }
    }


    public function acceptInvite(Invite $invite) {
        $request = $this->webResourceService->getHttpClientService()->postRequest(
            $this->getUrl('teaminvite_accept'),
            null,
            [
                'team' => $invite->getTeam()
            ]
        );

        $this->addAuthorisationToRequest($request);

        try {
            $this->webResourceService->get($request);
            return true;
        } catch (WebResourceException $webResourceException) {
            return false;
        }
    }


    /**
     * @return Invite[]
     */
    public function getForTeam() {
        $request = $this->webResourceService->getHttpClientService()->getRequest(
            $this->getUrl('team_invites')
        );

        $this->addAuthorisationToRequest($request);

        $inviteData = $this->webResourceService->get($request)->getContentObject();
        $invites = [];

        foreach ($inviteData as $rawInvite) {
            $invites[] = new Invite($rawInvite);
        }

        return $invites;
    }


    public function removeForUser(Invite $invite) {
        $request = $this->webResourceService->getHttpClientService()->postRequest(
            $this->getUrl('teaminvite_remove', [
                'invitee_email' => $invite->getUser()
            ])
        );

        $this->addAuthorisationToRequest($request);

        try {
            $this->webResourceService->get($request);
            return true;
        } catch (WebResourceException $webResourceException) {
            return false;
        }
    }


    /**
     * @param string $token
     * @return Invite
     */
    public function getForToken($token) {
        if (!isset($this->inviteCache[$token])) {
            $decodedResponse = json_decode($this->getAdminResponse($this->webResourceService->getHttpClientService()->getRequest($this->getUrl('teaminvite_getbytoken', [
                'token' => $token
            ])))->getBody());

            if ($decodedResponse instanceof \stdClass) {
                $this->inviteCache[$token] = new Invite($decodedResponse);
            } else {
                $this->inviteCache[$token] = null;
            }
        }

        return $this->inviteCache[$token];
    }


    /**
     * @param $token
     * @return bool
     */
    public function hasForToken($token) {
        return !is_null($this->getForToken($token));
    }


    /**
     *
     * @param \Guzzle\Http\Message\Request $request
     * @return boolean
     * @throws CoreApplicationAdminRequestException
     */
    private function getAdminResponse(\Guzzle\Http\Message\Request $request) {
        $currentUser = $this->getUser();

        $this->setUser($this->userService->getAdminUser());
        $this->addAuthorisationToRequest($request);

        try {
            $response = $request->send();
        } catch (\Guzzle\Http\Exception\BadResponseException $badResponseException) {
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