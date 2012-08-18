<?php

namespace SimplyTestable\WebClientBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class AppController extends BaseViewController
{
    public function indexAction()
    {        
        return $this->render('SimplyTestableWebClientBundle:App:index.html.twig', array(
            'test_start_url' => $this->generateUrl('test_start')
        ));
    }
}
