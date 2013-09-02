<?php

namespace SimplyTestable\WebClientBundle\Tests\Controller\App\QueuedAction;

use SimplyTestable\WebClientBundle\Tests\Controller\App\FunctionalTest as BaseFunctionalTest;
use SimplyTestable\WebClientBundle\Model\User;

class FunctionalTest extends BaseFunctionalTest {    
    
    protected function getActionName() {
        return 'queuedAction';
    }

    protected function getRoute() {
        return 'app_queued';
    }
    
    public function testWithAuthorisedUserWithQueuedTest() {
        $this->setUser(new User('user@example.com','password'));
        
        $testOptions = new \SimplyTestable\WebClientBundle\Model\TestOptions();
        $testOptions->addTestType('HTML validation');
        
        $this->getTestQueueService()->clear();
        
        $this->getTestQueueService()->enqueue(
            $this->getUser(),
            'http://example.com/',
            $testOptions,
            'full site',
            503
        );
        
        $crawler = $this->getCrawler($this->getCurrentRequestUrl(array(
            'website' => 'http://example.com'         
        )));
        
        $this->AuthorisedUserNavbarContentTest($crawler);           
    }

}


