<?php

namespace SimplyTestable\WebClientBundle\Tests\Functional\Controller\Action\User\Account\Team;

use SimplyTestable\WebClientBundle\Controller\Action\User\Account\TeamController;
use SimplyTestable\WebClientBundle\Tests\Functional\BaseSimplyTestableTestCase;

abstract class AbstractTeamControllerTest extends BaseSimplyTestableTestCase
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

        $this->teamController = new TeamController();
        $this->teamController->setContainer($this->container);
    }
}
