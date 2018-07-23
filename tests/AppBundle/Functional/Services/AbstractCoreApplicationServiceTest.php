<?php

namespace Tests\AppBundle\Functional\Services;

use App\Services\UserManager;
use Tests\AppBundle\Functional\AbstractBaseTestCase;
use Tests\AppBundle\Services\HttpMockHandler;
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

        $this->userManager = self::$container->get(UserManager::class);
        $this->httpMockHandler = self::$container->get(HttpMockHandler::class);
        $this->httpHistory = self::$container->get(HttpHistoryContainer::class);
    }
}
