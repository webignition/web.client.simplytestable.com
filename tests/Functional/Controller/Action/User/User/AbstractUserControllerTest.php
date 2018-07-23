<?php

namespace App\Tests\Functional\Controller\Action\User\User;

use App\Controller\Action\User\UserController;
use App\Tests\Functional\AbstractBaseTestCase;
use App\Tests\Functional\Controller\AbstractControllerTest;
use App\Tests\Services\HttpMockHandler;

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
