<?php

namespace SimplyTestable\WebClientBundle\Tests\Controller\App\IndexAction;

use SimplyTestable\WebClientBundle\Tests\Controller\App\FunctionalTest as BaseFunctionalTest;

class FunctionalTest extends BaseFunctionalTest {    
    
    protected function getActionName() {
        return 'indexAction';
    }    
    
    protected function getRoute() {
        return 'app';
    }    
    
    public function testPublicUserContent() {
        $crawler = $this->getCrawler($this->getCurrentRequestUrl());   
        $this->publicUserNavbarContentTest($crawler);     
    }

}


