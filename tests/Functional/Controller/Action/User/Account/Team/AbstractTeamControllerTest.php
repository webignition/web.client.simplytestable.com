<?php

namespace App\Tests\Functional\Controller\Action\User\Account\Team;

use App\Controller\Action\User\Account\TeamController;
use App\Tests\Functional\Controller\Action\User\Account\AbstractUserAccountControllerTest;

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
