<?php
namespace SimplyTestable\WebClientBundle\Services;

use SimplyTestable\WebClientBundle\Model\Team\Team;
use webignition\WebResource\JsonDocument\JsonDocument;

class TeamService extends CoreApplicationService
{
    /**
     * @var CoreApplicationRouter
     */
    private $coreApplicationRouter;

    /**
     * @var Team[]
     */
    private $teams = [];

    /**
     * @param array $parameters
     * @param WebResourceService $webResourceService
     * @param CoreApplicationRouter $coreApplicationRouter
     */
    public function __construct(
        array $parameters,
        WebResourceService $webResourceService,
        CoreApplicationRouter $coreApplicationRouter
    ) {
        parent::__construct($parameters, $webResourceService);

        $this->coreApplicationRouter = $coreApplicationRouter;
    }

    /**
     * @param string $name
     */
    public function create($name)
    {
        $request = $this->webResourceService->getHttpClientService()->postRequest(
            $this->coreApplicationRouter->generate('team_create'),
            null,
            [
                'name' => $name,
            ]
        );

        $this->addAuthorisationToRequest($request);
        $request->send();
    }

    /**
     * @return Team
     * @throws \Exception
     * @throws \Guzzle\Http\Exception\CurlException
     */
    public function getTeam()
    {
        $username = $this->getUser()->getUsername();

        if (!isset($this->teams[$username])) {
            $request = $this->webResourceService->getHttpClientService()->getRequest(
                $this->coreApplicationRouter->generate('team_get')
            );

            $this->addAuthorisationToRequest($request);

            /* @var JsonDocument $jsonDocument */
            $jsonDocument = $this->webResourceService->get($request);
            $this->teams[$username] = new Team($jsonDocument->getContentObject());
        }

        return $this->teams[$this->getUser()->getUsername()];
    }

    /**
     * @param string $member
     */
    public function removeFromTeam($member)
    {
        $request = $this->webResourceService->getHttpClientService()->postRequest(
            $this->coreApplicationRouter->generate('team_remove', [
                'member_email' => $member
            ])
        );

        $this->addAuthorisationToRequest($request);

        $request->send();
    }

    public function leave()
    {
        $request = $this->webResourceService->getHttpClientService()->postRequest(
            $this->coreApplicationRouter->generate('team_leave')
        );

        $this->addAuthorisationToRequest($request);
        $request->send();
    }
}
