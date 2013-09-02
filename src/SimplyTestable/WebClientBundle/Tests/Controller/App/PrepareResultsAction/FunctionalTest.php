<?php

namespace SimplyTestable\WebClientBundle\Tests\Controller\App\PrepareResultsAction;

use SimplyTestable\WebClientBundle\Tests\Controller\App\FunctionalTest as BaseFunctionalTest;
use SimplyTestable\WebClientBundle\Model\User;

class FunctionalTest extends BaseFunctionalTest {    
    
    protected function getActionName() {
        return 'prepareResultsAction';
    }

    protected function getRoute() {
        return 'app_results_preparing';
    }
    
    public function testWithAuthorisedUser() {
        $this->setUser(new User('user@example.com','password'));
        $this->setHttpFixtures($this->getHttpFixtures($this->getFixturesDataPath(__FUNCTION__ . '/HttpResponses')));
        
        $crawler = $this->getCrawler($this->getCurrentRequestUrl(array(
            'website' => 'http://example.com',
            'test_id' => 1            
        )));
        
        $this->AuthorisedUserNavbarContentTest($crawler);   
    }

}


