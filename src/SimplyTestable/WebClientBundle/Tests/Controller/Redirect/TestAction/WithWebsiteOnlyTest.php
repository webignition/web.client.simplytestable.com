<?php

namespace SimplyTestable\WebClientBundle\Tests\Controller\Redirect\TestAction;

class WithWebsiteOnlyTest extends ActionTest {

    public function testWithWebSiteOnly() {
        $website = 'http://example.com/';
        
        $this->performActionTest(array(
            'statusCode' => 302,
            'redirectPath' => '/'
        ), array(
            'postData' => array(
                'website' => $website
            ),
            'methodArguments' => array(
                $website
            )
        ));
    }    

}


