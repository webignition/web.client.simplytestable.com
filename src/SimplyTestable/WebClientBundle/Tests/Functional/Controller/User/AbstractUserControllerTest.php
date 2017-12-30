<?php

namespace SimplyTestable\WebClientBundle\Tests\Functional\Controller\User;

use SimplyTestable\WebClientBundle\Controller\UserController;
use SimplyTestable\WebClientBundle\Tests\Functional\BaseSimplyTestableTestCase;

abstract class AbstractUserControllerTest extends BaseSimplyTestableTestCase
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
