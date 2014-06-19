<?php

namespace SimplyTestable\WebClientBundle\Controller\View\Test;

use SimplyTestable\WebClientBundle\Interfaces\Controller\Cacheable;

abstract class CacheableViewController extends ViewController implements Cacheable {
    
    /**
     *
     * @var \Symfony\Component\HttpFoundation\Request
     */
    private $request;
    
    
    /**
     * 
     * @param \Symfony\Component\HttpFoundation\Request $request
     */
    public function setRequest(\Symfony\Component\HttpFoundation\Request $request) {
        $this->request = $request;
    }   
    
    
    /**
     * 
     * @return \Symfony\Component\HttpFoundation\Request
     */
    public function getRequest() {
        return (is_null($this->request)) ? $this->get('request') : $this->request;
    }


    /**
     * @return bool
     */
    protected function isXmlHttpRequest() {
        return $this->getRequest()->headers->has('X-Requested-With') && $this->getRequest()->headers->get('X-Requested-With') == 'XMLHttpRequest';
    }


    /**
     * @param string $locationValue
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    protected function issueRedirect($locationValue) {
        if  ($this->isXmlHttpRequest()) {
            return $this->renderResponse($this->getRequest(), [
                'this_url' => $locationValue
            ]);
        }

        return $this->redirect($locationValue);
    }

}