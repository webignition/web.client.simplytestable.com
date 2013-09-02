<?php

namespace SimplyTestable\WebClientBundle\Tests\Controller;

use SimplyTestable\WebClientBundle\Tests\Controller\BaseTest;

abstract class FunctionalTest extends BaseTest {    
    
    abstract protected function getRoute();
    
    protected function getCurrentRequestUrl($parameters = null) {
        $parameters = (is_array($parameters)) ? $parameters : array();
        
        return $this->getCurrentController()->generateUrl($this->getRoute(), $parameters);
    }    
    
    protected function publicUserNavbarContentTest(\Symfony\Component\DomCrawler\Crawler $crawler) {
        /* @var $navbar \Symfony\Component\DomCrawler\Crawler */
        $navbar = $crawler->filter('.navbar');
        
        $signInButton = $navbar->filter('a:contains("Sign in")');        
        $this->assertEquals(1, $signInButton->count());
        $this->assertEquals(array('/signin/'), $signInButton->extract('href'));       
        
        $signUpButton = $navbar->filter('a:contains("Create account")');        
        $this->assertEquals(1, $signUpButton->count());
        $this->assertEquals(array('/signup/'), $signUpButton->extract('href'));           
    }    

    protected function AuthorisedUserNavbarContentTest(\Symfony\Component\DomCrawler\Crawler $crawler) {        
        if (!$this->hasUser()) {
            $this->fail('Cannot verify authorised user navbar content; no user set');
        }
        
        /* @var $navbar \Symfony\Component\DomCrawler\Crawler */
        $navbar = $crawler->filter('.navbar');
        
        $accountButton = $navbar->filter('a:contains("'.$this->getUser()->getUsername().'")');        
        $this->assertEquals(1, $accountButton->count());
        $this->assertEquals(array('/account/'), $accountButton->extract('href'));       
        
        $signOutButton = $navbar->filter('button:contains("Sign out")');        
        $this->assertEquals(1, $signOutButton->count());
        
        $signOutForm = $navbar->filter('form');        
        $this->assertEquals(array('/signout/'), $signOutForm->extract('action'));
    }        
    
}


