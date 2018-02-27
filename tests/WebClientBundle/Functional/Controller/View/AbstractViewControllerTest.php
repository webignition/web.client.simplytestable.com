<?php

namespace Tests\WebClientBundle\Functional\Controller\View;

use ReflectionClass;
use SimplyTestable\WebClientBundle\Controller\BaseViewController;
use Tests\WebClientBundle\Functional\AbstractBaseTestCase;
use Twig_Environment;

abstract class AbstractViewControllerTest extends AbstractBaseTestCase
{
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
