<?php

namespace SimplyTestable\WebClientBundle\Tests\Controller\User\View\SignUp\IndexAction;

use SimplyTestable\WebClientBundle\Tests\Controller\Base\ActionTest as BaseActionTest;

class PostSignupRedirectParametersAreStoredInCookieTest extends BaseActionTest {        
  
    protected function getExpectedResponseStatusCode() {
        return 200;
    }    
    
    protected function getRequestQueryData() {
        return array(
            'redirect' => 'foo'
        );
    }
    
    
    public function testRedirectCookieValue() {
        $this->assertCookieValue('simplytestable-redirect', 'foo');
    }

}


 