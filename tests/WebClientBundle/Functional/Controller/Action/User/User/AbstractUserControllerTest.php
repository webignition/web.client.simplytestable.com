<?php

namespace Tests\WebClientBundle\Functional\Controller\Action\User\User;

use SimplyTestable\WebClientBundle\Controller\Action\User\UserController;
use Tests\WebClientBundle\Functional\AbstractBaseTestCase;
use Tests\WebClientBundle\Functional\Controller\AbstractControllerTest;
use Tests\WebClientBundle\Services\HttpMockHandler;

abstract class AbstractUserControllerTest extends AbstractControllerTest
{
    /**
     * @var UserController
     */
    protected $userController;

    /**
     * @var HttpMockHandler
     */
    protected $httpMockHandler;

    /**
     * {@inheritdoc}
     */
    protected function setUp()
    {
        parent::setUp();

        $this->userController = self::$container->get(UserController::class);
        $this->httpMockHandler = self::$container->get(HttpMockHandler::class);
    }
}
