<?php

namespace SimplyTestable\WebClientBundle\Tests\Functional\Services\TeamInvite;

use Guzzle\Plugin\History\HistoryPlugin;
use SimplyTestable\WebClientBundle\Model\User;
use SimplyTestable\WebClientBundle\Services\TeamInviteService;
use SimplyTestable\WebClientBundle\Tests\Functional\BaseSimplyTestableTestCase;

abstract class AbstractTeamInviteServiceTest extends BaseSimplyTestableTestCase
{
    /**
     * @var TeamInviteService
     */
    protected $teamInviteService;

    /**
     * @var User
     */
    protected $user;

    /**
     * @var HistoryPlugin
     */
    protected $httpHistoryPlugin;

    /**
     * {@inheritdoc}
     */
    protected function setUp()
    {
        parent::setUp();

        $this->teamInviteService = $this->container->get(
            'simplytestable.services.teaminviteservice'
        );

        $this->user = new User('user@example.com');

        $this->httpHistoryPlugin = new HistoryPlugin();

        $httpClientService = $this->getHttpClientService();
        $httpClientService->get()->addSubscriber($this->httpHistoryPlugin);
    }
}
