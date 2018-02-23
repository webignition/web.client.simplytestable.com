<?php

namespace Tests\WebClientBundle\Functional\Controller\Action\User\Account\EmailChange;

use SimplyTestable\WebClientBundle\Controller\Action\User\Account\EmailChangeController;
use Tests\WebClientBundle\Functional\Controller\Action\User\Account\AbstractUserAccountControllerTest;

abstract class AbstractEmailChangeControllerTest extends AbstractUserAccountControllerTest
{
    /**
     * @var EmailChangeController
     */
    protected $emailChangeController;

    /**
     * {@inheritdoc}
     */
    protected function setUp()
    {
        parent::setUp();

        $this->emailChangeController = new EmailChangeController();
        $this->emailChangeController->setContainer($this->container);
    }
}
