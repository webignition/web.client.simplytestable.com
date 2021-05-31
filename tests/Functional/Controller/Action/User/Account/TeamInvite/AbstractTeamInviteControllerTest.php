<?php

namespace App\Tests\Functional\Controller\Action\User\Account\TeamInvite;

use App\Controller\Action\User\Account\TeamController;
use App\Controller\Action\User\Account\TeamInviteController;
use App\Tests\Functional\Controller\Action\User\Account\AbstractUserAccountControllerTest;

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

        $this->teamInviteController = self::$container->get(TeamInviteController::class);
    }
}
