<?php

namespace App\Tests\Functional\Controller\View;

use ReflectionClass;
use App\Controller\AbstractBaseViewController;
use App\Tests\Functional\Controller\AbstractControllerTest;
use App\Tests\Services\HttpMockHandler;
use webignition\HttpHistoryContainer\Container as HttpHistoryContainer;
use Twig_Environment;

abstract class AbstractViewControllerTest extends AbstractControllerTest
{
    /**
     * @var HttpHistoryContainer
     */
    protected $httpHistory;

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

        $this->httpHistory = self::$container->get(HttpHistoryContainer::class);
        $this->httpMockHandler = self::$container->get(HttpMockHandler::class);
    }

    /**
     * @param Twig_Environment $twig
     * @param AbstractBaseViewController $controller
     *
     * @throws \ReflectionException
     */
    protected function setTwigOnController(Twig_Environment $twig, AbstractBaseViewController $controller)
    {
        $reflectionClass = new ReflectionClass(AbstractBaseViewController::class);

        $reflectionProperty = $reflectionClass->getProperty('twig');
        $reflectionProperty->setAccessible(true);

        $reflectionProperty->setValue($controller, $twig);
    }
}
