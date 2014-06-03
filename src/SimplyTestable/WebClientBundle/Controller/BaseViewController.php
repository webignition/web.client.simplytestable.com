<?php

namespace SimplyTestable\WebClientBundle\Controller;

use Negotiation\FormatNegotiator;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
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
        $response->headers->addCacheControlDirective('must-revalidate', true);
        
        return $response;
    }
    
    
    /**
     * 
     * @param \Symfony\Component\HttpFoundation\Response $response
     * @return \Symfony\Component\HttpFoundation\Response
     */
    protected function getUncacheableResponse(Response $response) {
        $response->setPublic();
        $response->setMaxAge(0);
        $response->headers->addCacheControlDirective('must-revalidate', true);
        $response->headers->addCacheControlDirective('no-cache', true);
        
        return $response;
    }


    /**
     * @param Request $request
     * @param array $additionalParameters
     * @return Response
     */
    protected function renderResponse(Request $request, array $additionalParameters = array()) {
        if ($request->headers->has('accept')) {
            $negotiator   = new FormatNegotiator();
            $priorities   = array('text/html', 'application/json', '*/*');
            $format = $negotiator->getBest($request->headers->get('accept'), $priorities);

            if ($format->getValue() == 'application/json') {
                $response = new Response($this->getSerializer()->serialize($additionalParameters, 'json'));
                $response->headers->set('Content-Type', 'application/json');
                return $response;
            }
        }

        return parent::render($this->getViewName(), array_merge($this->getDefaultViewParameters(), $additionalParameters));
    }     
    
    
    /**
     * 
     * @param array $additionalParameters
     * @return \Symfony\Component\HttpFoundation\Response
     */    
    protected function renderCacheableResponse(array $additionalParameters = array()) {
/*
        if ($this->isJsonResponseRequired()) {
            $response = new Response($this->getSerializer()->serialize($viewData, 'json'));
            $response->headers->set('Content-Type', 'application/json');
            return $response;
        }
 */



        return $this->getCacheableResponseService()->getCachableResponse(
            $this->getRequest(), $this->renderResponse(
                $this->getRequest(),
                $additionalParameters
            )
        );        
    }
    
    
    /**
     * 
     * @return \SimplyTestable\WebClientBundle\Services\CacheableResponseService
     */
    protected function getCacheableResponseService() {
        return $this->get('simplytestable.services.cacheableResponseService');
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
        $identifier->setParameter('is_logged_in', !$this->getUserService()->isPublicUser($this->getUser()));
        
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
        if (!preg_match('/msie 6\.[0-9]+/i', $this->get('request')->server->get('HTTP_USER_AGENT'))) {
            return false;
        }
        
        if (preg_match('/opera/i', $this->get('request')->server->get('HTTP_USER_AGENT'))) {
            return false;
        }
        
        if (preg_match('/msie 8.[0-9]+/i', $this->get('request')->server->get('HTTP_USER_AGENT'))) {
            return false;
        }
        
        return true;
    }
    
    
    /**
     * 
     * @return boolean
     */
    private function isUsingIE7() {        
        if (!preg_match('/msie 7\.[0-9]+/i', $this->get('request')->server->get('HTTP_USER_AGENT'))) {
            return false;
        }
        
        return true;
    }    
    
    
    /**
     *
     * @param type $flashKey
     * @return string|null 
     */
    protected function getFlash($flashKey, $flush = true) {        
        if ($flush) {
            $flashMessages = $this->get('session')->getFlashBag()->get($flashKey);         
        } else {
            $flashMessages = $this->get('session')->getFlashBag()->peek($flashKey);         
        }        
        
        if (!isset($flashMessages[0])) {
            return '';
        }
        
        return $flashMessages[0];
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
    
    /**
     *
     * @return \JMS\SerializerBundle\Serializer\Serializer
     */
    protected function getSerializer() {
        return $this->container->get('serializer');
    } 
    
    
    
    protected function getViewName() {
        $classNamespaceParts = $this->getClassNamespaceParts();
        $bundleNamespaceParts = array_slice($classNamespaceParts, 0, array_search('Controller', $classNamespaceParts));
        
        return $this->modifyViewName(implode('', $bundleNamespaceParts) . ':' .  $this->getViewPath() . ':' . $this->getViewFilename());
    }
    
    
    /**
     * 
     * @return string
     */
    protected function getViewPath() {
        $classNamespaceParts = $this->getClassNamespaceParts();
        $controllerClassNameParts = array_slice($classNamespaceParts, array_search('Controller', $classNamespaceParts) + 1);

        array_walk($controllerClassNameParts, function(&$part) {            
            $part = preg_replace(array(
                '/Controller$/',
                '/^View$/',
            ), '', $part);
        });
        
        foreach ($controllerClassNameParts as $index => $part) {
            if ($part == '') {
                unset($controllerClassNameParts[$index]);
            }
        }

        return implode('/', $controllerClassNameParts);
    }
    
    
    /**
     * 
     * @return string
     */
    protected function getViewFilename() {
        $routeParts = explode('_', $this->container->get('request')->get('_route'));                
        return $routeParts[count($routeParts) - 1] . '.html.twig';
    }

    
    /**
     * 
     * @return string[]
     */
    private function getClassNamespaceParts() {
        return explode('\\', get_class($this));
    }  
    
    
    /**
     * 
     * @return array
     */
    protected function getDefaultViewParameters() {
        return array(
            'user' => $this->getUserService()->getUser(),
            'is_logged_in' => $this->getUserService()->isLoggedIn(),
            'public_site' => $this->container->getParameter('public_site'),
            'external_links' => $this->container->getParameter('external_links')
        );
    } 
    
    
    protected function modifyViewName($viewName) {
        return $viewName;
    }
    
    
    /**
     * 
     * @param array $keys
     * @return array
     */
    protected function getViewFlashValues($keys) {
        $flashValues = array();
        
        foreach ($keys as $key) {
            $flashValues[$key] = $this->getFlash($key);
        }
        
        return $flashValues;
    }    
    
}
