<?php

namespace Tests\AppBundle\Functional\Controller\Action\User\Account\Team;

use AppBundle\Controller\Action\User\Account\TeamController;
use Tests\AppBundle\Functional\Controller\Action\User\Account\AbstractUserAccountControllerTest;

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

        $this->teamController = self::$container->get(TeamController::class);
    }
}
