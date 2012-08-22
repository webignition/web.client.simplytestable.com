<?php

namespace SimplyTestable\WebClientBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;

use webignition\NormalisedUrl\NormalisedUrl;

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
    
    
    /**
     *
     * @param string $url
     * @param int $status
     * @return \Symfony\Component\HttpFoundation\RedirectResponse 
     */
    public function redirect($url, $status = 302) {        
        if ($this->isJsonResponseRequired()) {
            $normalisedUrl = new NormalisedUrl($url);

            if ($normalisedUrl->hasQuery()) {                
                $normalisedUrl->getQuery()->set('output', 'json');
            } else {
                $normalisedUrl->setQuery('output=json');
            }            
            
            $url = (string)$normalisedUrl;
        }
        
        return parent::redirect($url, $status);
    }
    
}
