<?php

namespace Tests\AppBundle\Functional\Controller\Action\User\Account\EmailChange;

use App\Controller\Action\User\Account\EmailChangeController;
use Tests\AppBundle\Functional\Controller\Action\User\Account\AbstractUserAccountControllerTest;

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

        $this->emailChangeController = self::$container->get(EmailChangeController::class);
    }
}
