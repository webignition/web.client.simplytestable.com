<?php

namespace SimplyTestable\WebClientBundle\Tests\Controller\App;

use SimplyTestable\WebClientBundle\Tests\Controller\ControllerFunctionalTest;

class AppControllerIndexActionFunctionalTest extends ControllerFunctionalTest {    
    
    const INDEX_ACTION_ROUTE = '/';
    
    public static function setUpBeforeClass() {
        self::setupDatabaseIfNotExists();
    }
    
    public function testPublicUserContent() {
        /* @var $crawler \Symfony\Component\DomCrawler\Crawler */
        $crawler = $this->client->request('GET', self::INDEX_ACTION_ROUTE);
        
        $this->publicUserNavbarContentTest($crawler);
        
        //var_dump($crawler->filter('.navbar')->count());
        
        /* @var $navbar \Symfony\Component\DomCrawler\Crawler */
        //$navbar = $crawler->filter('.navbar');
        
        //var_dump($navbar->filter('a:contains("Sign in")')->text());
        
        //$navbar->filter('a:contains("Sign in")');
        
        
//        
//        var_dump($navbar);
//        
//        $link = $navbar->filter('a:contains("Sign in")')->eq(1)->link();
//        
//        var_dump($link);
        
//            $this->assertGreaterThan(
//                0,
//                $crawler->filter('html:contains("Hello Fabien")')->count()
//            );        
    }
}


