<?php

namespace SimplyTestable\WebClientBundle\Tests\Functional\Controller\Redirect\TestAction;

class WithWebsiteAndTestIdTest extends ActionTest {

    public function testWithUnauthorisedOrNonExistentTest() {
        $website = 'http://example.com/';
        $test_id = 1;
        
        $this->performActionTest(array(
            'statusCode' => 302,
            'redirectPath' => '/http://example.com//'
        ), array(
            'postData' => array(
                'website' => $website,
                'test_id' => $test_id
            ),
            'methodArguments' => array(
                $website,
                $test_id
            )
        ));
    }

}


