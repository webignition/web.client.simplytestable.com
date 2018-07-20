<?php

namespace Tests\AppBundle\Functional\Controller\Action\User\User;

use AppBundle\Controller\Action\User\UserController;
use Tests\AppBundle\Functional\AbstractBaseTestCase;
use Tests\AppBundle\Functional\Controller\AbstractControllerTest;
use Tests\AppBundle\Services\HttpMockHandler;

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
