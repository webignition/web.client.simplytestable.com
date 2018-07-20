<?php

namespace Tests\AppBundle\Functional\Controller\Action\User\Account\TeamInvite;

use AppBundle\Controller\Action\User\Account\TeamController;
use AppBundle\Controller\Action\User\Account\TeamInviteController;
use Tests\AppBundle\Functional\Controller\Action\User\Account\AbstractUserAccountControllerTest;

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
