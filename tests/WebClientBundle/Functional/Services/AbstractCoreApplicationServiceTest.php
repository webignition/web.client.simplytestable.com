<?php

namespace Tests\WebClientBundle\Functional\Services;

use SimplyTestable\WebClientBundle\Services\UserManager;
use Tests\WebClientBundle\Functional\AbstractBaseTestCase;
use Tests\WebClientBundle\Services\HttpMockHandler;
use webignition\HttpHistoryContainer\Container as HttpHistoryContainer;

abstract class AbstractCoreApplicationServiceTest extends AbstractBaseTestCase
{
    /**
     * @var UserManager
     */
    protected $userManager;

    /**
     * @var HttpMockHandler
     */
    protected $httpMockHandler;

    /**
     * @var HttpHistoryContainer
     */
    protected $httpHistory;

    /**
     * {@inheritdoc}
     */
    protected function setUp()
    {
        parent::setUp();

        $this->userManager = $this->container->get(UserManager::class);
        $this->httpMockHandler = $this->container->get(HttpMockHandler::class);
        $this->httpHistory = $this->container->get(HttpHistoryContainer::class);
    }
}
