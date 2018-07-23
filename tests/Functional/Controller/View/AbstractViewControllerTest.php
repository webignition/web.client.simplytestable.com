<?php

namespace App\Tests\Functional\Controller\View;

use ReflectionClass;
use App\Controller\BaseViewController;
use App\Tests\Functional\AbstractBaseTestCase;
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
     * @param BaseViewController $controller
     *
     * @throws \ReflectionException
     */
    protected function setTwigOnController(Twig_Environment $twig, BaseViewController $controller)
    {
        $reflectionClass = new ReflectionClass(BaseViewController::class);

        $reflectionProperty = $reflectionClass->getProperty('twig');
        $reflectionProperty->setAccessible(true);

        $reflectionProperty->setValue($controller, $twig);
    }
}
