<?php

namespace SimplyTestable\WebClientBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;

abstract class BaseViewController extends Controller
{    
    private $template;
    
    protected function setTemplate($template)
    {
        $this->template = $template;
    }
    
    
    protected function sendResponse($viewData)
    {
        if ($this->isJsonResponseRequired()) {
            $response = new Response(json_encode($viewData));
            $response->headers->set('Content-Type', 'application/json');
            return $response;
        }
        
        return $this->render($this->template, $viewData);
    }
    
    
    /**
     *
     * @return boolean 
     */
    private function isJsonResponseRequired() {
        return $this->get('request')->get('output') == 'json';   
    }
    
}
