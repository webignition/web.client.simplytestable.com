<?php

namespace Tests\WebClientBundle\Functional\Controller\View;

use ReflectionClass;
use SimplyTestable\WebClientBundle\Controller\BaseViewController;
use Tests\WebClientBundle\Functional\AbstractBaseTestCase;
use Tests\WebClientBundle\Functional\Controller\AbstractControllerTest;
use Tests\WebClientBundle\Services\HttpMockHandler;
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
