<?php

namespace Tests\WebClientBundle\Functional\Controller\Action\User\Account\Team;

use SimplyTestable\WebClientBundle\Controller\Action\User\Account\TeamController;
use Tests\WebClientBundle\Functional\Controller\Action\User\Account\AbstractUserAccountControllerTest;

abstract class AbstractTeamControllerTest extends AbstractUserAccountControllerTest
{
    /**
     * @var TeamController
     */
    protected $teamController;

    /**
     * {@inheritdoc}
     */
    protected function setUp()
    {
        parent::setUp();

        $this->teamController = $this->container->get(TeamController::class);
    }
}
