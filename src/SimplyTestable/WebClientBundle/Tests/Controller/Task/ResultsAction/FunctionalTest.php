<?php

namespace SimplyTestable\WebClientBundle\Tests\Controller\Task\ResultsAction;

use SimplyTestable\WebClientBundle\Tests\Controller\Task\FunctionalTest as BaseFunctionalTest;
use SimplyTestable\WebClientBundle\Model\User;

class FunctionalTest extends BaseFunctionalTest {    
    
    protected function getActionName() {
        return 'resultsAction';
    }

    protected function getRoute() {
        return 'app_task_results';
    }
    
    public function testWithAuthorisedUserWithValidTestAndValidTask() {
        $this->setUser(new User('user@example.com','password'));
        $this->setHttpFixtures($this->getHttpFixtures($this->getFixturesDataPath(__FUNCTION__ . '/HttpResponses')));
        
        $crawler = $this->getCrawler($this->getCurrentRequestUrl(array(
            'website' => 'http://example.com',
            'test_id' => 1,
            'task_id' => 1
        )));
        
        $this->AuthorisedUserNavbarContentTest($crawler);          
    }

}


