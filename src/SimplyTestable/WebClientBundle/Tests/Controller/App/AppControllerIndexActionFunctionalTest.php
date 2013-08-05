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
    }
}


