<?php

namespace SimplyTestable\WebClientBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

abstract class BaseViewController extends Controller
{    
    /**
     *
     * @return \Symfony\Bundle\FrameworkBundle\Routing\Router 
     */
    protected function getRouter() {
        return $this->container->get('router');
    }
}
