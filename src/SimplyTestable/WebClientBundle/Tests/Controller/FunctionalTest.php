<?php

namespace SimplyTestable\WebClientBundle\Tests\Controller;

use SimplyTestable\WebClientBundle\Tests\Controller\BaseTest;

abstract class FunctionalTest extends BaseTest {    
    
    abstract protected function getRoute();
    
    protected function getCurrentRequestUrl($parameters = null) {
        $parameters = (is_array($parameters)) ? $parameters : array();
        
        return $this->getCurrentController()->generateUrl($this->getRoute(), $parameters);
    } 
    
    protected function publicUserNavbarContainsSignInButtonTest(\Symfony\Component\DomCrawler\Crawler $crawler) {        
        $button = $this->getNavbar($crawler)->filter('a:contains("Sign in")');        
        $this->assertEquals(1, $button->count());     
    }    
    
    protected function publicUserNavbarSignInButtonUrlTest(\Symfony\Component\DomCrawler\Crawler $crawler) {        
        $button = $this->getNavbar($crawler)->filter('a:contains("Sign in")');
        $this->assertEquals(array('/signin/'), $button->extract('href'));              
    }
    
    protected function publicUserNavbarContainsCreateAccountButtonTest(\Symfony\Component\DomCrawler\Crawler $crawler) {        
        $button = $this->getNavbar($crawler)->filter('a:contains("Create account")');        
        $this->assertEquals(1, $button->count());          
    }
    
    protected function publicUserNavbarCreateAccountButtonUrlTest(\Symfony\Component\DomCrawler\Crawler $crawler) {        
        $button = $this->getNavbar($crawler)->filter('a:contains("Create account")');        
        $this->assertEquals(array('/signup/'), $button->extract('href'));         
    }
    
    protected function privateUserNavbarContainsAccountLinkTest(\Symfony\Component\DomCrawler\Crawler $crawler) {        
        if (!$this->hasUser()) {
            $this->fail('Cannot verify authorised user navbar content; no user set');
        }
        
        $button = $this->getNavbar($crawler)->filter('a:contains("'.$this->getUser()->getUsername().'")');        
        $this->assertEquals(1, $button->count());
    }    
    
    protected function privateUserNavbarAccountLinkUrlTest(\Symfony\Component\DomCrawler\Crawler $crawler) {        
        if (!$this->hasUser()) {
            $this->fail('Cannot verify authorised user navbar content; no user set');
        }
        
        $button = $this->getNavbar($crawler)->filter('a:contains("'.$this->getUser()->getUsername().'")');        
        $this->assertEquals(array('/account/'), $button->extract('href'));
    } 
    
    protected function privateUserNavbarContainsSignOutButtonTest(\Symfony\Component\DomCrawler\Crawler $crawler) {        
        if (!$this->hasUser()) {
            $this->fail('Cannot verify authorised user navbar content; no user set');
        }
        
        $button = $this->getNavbar($crawler)->filter('button:contains("Sign out")');        
        $this->assertEquals(1, $button->count());
    }    
    
    protected function privateUserNavbarSignOutFormUrlTest(\Symfony\Component\DomCrawler\Crawler $crawler) {        
        if (!$this->hasUser()) {
            $this->fail('Cannot verify authorised user navbar content; no user set');
        }
        
        $form = $this->getNavbar($crawler)->filter('form');        
        $this->assertEquals(array('/signout/'), $form->extract('action'));
    }    
    
    
    /**
     * 
     * @param \Symfony\Component\DomCrawler\Crawler $crawler
     * @return \Symfony\Component\DomCrawler\Crawler
     */
    private function getNavbar(\Symfony\Component\DomCrawler\Crawler $crawler) {
        return $crawler->filter('.navbar');
    }
    
    protected function domNodeToHtml(\DOMNode $node) {
        return $node->ownerDocument->saveHTML($node);        
    }
    
}


