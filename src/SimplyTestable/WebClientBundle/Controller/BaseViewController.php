<?php

namespace SimplyTestable\WebClientBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use SimplyTestable\WebClientBundle\Model\CacheValidatorIdentifier;
use SimplyTestable\WebClientBundle\Entity\CacheValidatorHeaders;

use webignition\NormalisedUrl\NormalisedUrl;

abstract class BaseViewController extends BaseController
{    
    private $template;
    
    protected function setTemplate($template)
    {
        $this->template = $template;
    }
    
    
    protected function sendResponse($viewData)
    {
        if ($this->isJsonResponseRequired()) {            
            $response = new Response($this->getSerializer()->serialize($viewData, 'json'));
            $response->headers->set('Content-Type', 'application/json');
            return $response;
        }
        
        return $this->render($this->template, $viewData);
    }
    
    protected function sendNotFoundResponse() {
        return new Response('', 404);
    }
    
    
    /**
     *
     * @return boolean 
     */
    protected function isJsonResponseRequired() {
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
    
    
    /**
     *
     * @return \SimplyTestable\WebClientBundle\Services\CacheValidatorHeadersService 
     */
    protected function getCacheValidatorHeadersService() {
        return $this->container->get('simplytestable.services.cachevalidatorheadersservice');
    }   
    
    
    /**
     *
     * @param Response $response
     * @param CacheValidatorHeaders $cacheValidatorHeaders
     * @return \Symfony\Component\HttpFoundation\Response 
     */
    protected function getCachableResponse(Response $response, CacheValidatorHeaders $cacheValidatorHeaders) {
        $response->setPublic();
        $response->setEtag($cacheValidatorHeaders->getETag());
        $response->setLastModified($cacheValidatorHeaders->getLastModifiedDate());        
        
        return $response;
    }
    
    
    /**
     *
     * @param string $templateName
     * @return \DateTime 
     */
    protected function getTemplateLastModifiedDate($templateName) {
        return new \DateTime(date('c', filemtime($this->getTemplatePath($templateName))));
    }
    
    
    /**
     *
     * @param string $templateName
     * @return string
     */
    private function getTemplatePath($templateName) {
        $parser = $this->container->get('templating.name_parser');
        $locator = $this->container->get('templating.locator');

        return $locator->locate($parser->parse($templateName));         
    } 
    
    
    /**
     *
     * @return \SimplyTestable\WebClientBundle\Model\CacheValidatorIdentifier 
     */
    protected function getCacheValidatorIdentifier(array $parameters = array()) {        
        $identifier = new CacheValidatorIdentifier();
        $identifier->setParameter('route', $this->container->get('request')->get('_route'));
        $identifier->setParameter('user', $this->getUser()->getUsername());
        $identifier->setParameter('is_logged_in', $this->getUserService()->isPublicUser($this->getUser()) ? 'false' : 'true');
        
        foreach ($parameters as $key => $value) {
            $identifier->setParameter($key, $value);
        }
        
        return $identifier;
    }   
    
    
    /**
     * 
     * @return boolean
     */
    public function isUsingOldIE() {        
        return $this->isUsingIE6() || $this->isUsingIE7();           
    }
    
    
    /**
     * 
     * @return boolean
     */
    private function isUsingIE6() {        
        if (!preg_match('/msie 6\.[0-9]+/i', $_SERVER['HTTP_USER_AGENT'])) {
            return false;
        }
        
        if (preg_match('/opera/i', $_SERVER['HTTP_USER_AGENT'])) {
            return false;
        }
        
        return true;
    }
    
    
    /**
     * 
     * @return boolean
     */
    private function isUsingIE7() {        
        if (!preg_match('/msie 7\.[0-9]+/i', $_SERVER['HTTP_USER_AGENT'])) {
            return false;
        }
        
        return true;
    }    
    
    
    /**
     *
     * @param type $flashKey
     * @param type $messageIndex
     * @return string|null 
     */
    protected function getFlash($flashKey, $messageIndex = 0) {        
        $flashMessages = $this->get('session')->getFlashBag()->get($flashKey);         
        if (!isset($flashMessages[$messageIndex])) {
            return '';
        }
        
        return $flashMessages[$messageIndex];
    }
    
    
    protected function getPersistentValue($key, $default = null) {
        $flashValue = $this->getFlash($key);
        if ($flashValue != '') {
            return $flashValue;
        }
        
        $requestValue = $this->get('request')->query->get($key);
        return is_null($requestValue) ? $default : $requestValue;
    }
    
    
    /**
     * 
     * @param array $definition
     * @return array
     */
    protected function getPersistentValues($definition) {
        $values = array();
        
        foreach ($definition as $key => $default) {
            $values[$key] = $this->getPersistentValue($key, $default);
        }
        
        return $values;
    }
    
}
