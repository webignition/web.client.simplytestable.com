<?php

namespace Tests\WebClientBundle\Functional\Controller\User;

use SimplyTestable\WebClientBundle\Controller\UserController;
use Tests\WebClientBundle\Functional\AbstractBaseTestCase;

abstract class AbstractUserControllerTest extends AbstractBaseTestCase
{
    /**
     * @var UserController
     */
    protected $userController;

    /**
     * {@inheritdoc}
     */
    protected function setUp()
    {
        parent::setUp();

        $this->userController = new UserController();
    }
}
