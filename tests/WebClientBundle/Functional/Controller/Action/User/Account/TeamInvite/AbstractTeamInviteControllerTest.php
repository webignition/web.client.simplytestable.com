<?php

namespace Tests\WebClientBundle\Functional\Controller\Action\User\Account\TeamInvite;

use SimplyTestable\WebClientBundle\Controller\Action\User\Account\TeamController;
use SimplyTestable\WebClientBundle\Controller\Action\User\Account\TeamInviteController;
use Tests\WebClientBundle\Functional\Controller\Action\User\Account\AbstractUserAccountControllerTest;

abstract class AbstractTeamInviteControllerTest extends AbstractUserAccountControllerTest
{
    /**
     * @var TeamInviteController
     */
    protected $teamInviteController;

    /**
     * {@inheritdoc}
     */
    protected function setUp()
    {
        parent::setUp();

        $this->teamInviteController = $this->container->get(TeamInviteController::class);
    }
}