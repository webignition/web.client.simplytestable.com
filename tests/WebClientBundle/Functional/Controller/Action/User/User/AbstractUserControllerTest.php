<?php

namespace Tests\WebClientBundle\Functional\Controller\Action\User\User;

use SimplyTestable\WebClientBundle\Controller\Action\User\UserController;
use Tests\WebClientBundle\Functional\AbstractBaseTestCase;
use Tests\WebClientBundle\Services\HttpMockHandler;

abstract class AbstractUserControllerTest extends AbstractBaseTestCase
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

        $this->userController = $this->container->get(UserController::class);
        $this->httpMockHandler = $this->container->get(HttpMockHandler::class);
    }
}
