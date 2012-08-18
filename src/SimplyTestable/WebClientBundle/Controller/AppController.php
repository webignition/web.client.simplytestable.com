<?php

namespace SimplyTestable\WebClientBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class AppController extends BaseViewController
{
    public function indexAction()
    {        
        return $this->render('SimplyTestableWebClientBundle:App:index.html.twig', array(
            'test_start_url' => $this->generateUrl('test_start'),
            'test_start_error' => $this->hasFlash('test_start_error')
        ));
    }
    
    
    /**
     *
     * @param string $flashKey
     * @return boolean
     */
    private function hasFlash($flashKey) {
        $flashMessages = $this->get('session')->getFlashBag()->get($flashKey);
        return is_array($flashMessages) && count($flashMessages) > 0;
    }
    
    
}
