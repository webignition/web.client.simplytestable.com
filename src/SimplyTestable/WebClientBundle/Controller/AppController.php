<?php

namespace SimplyTestable\WebClientBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class AppController extends Controller
{
    public function indexAction()
    {
        return $this->render('SimplyTestableWebClientBundle:App:index.html.twig');
    }
}
