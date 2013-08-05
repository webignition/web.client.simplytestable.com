<?php

namespace SimplyTestable\WebClientBundle\Tests\Controller;

use SimplyTestable\WebClientBundle\Tests\BaseSimplyTestableTestCase;

class ControllerFunctionalTest extends BaseSimplyTestableTestCase {    
    

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
    
}


