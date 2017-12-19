<?php

namespace SimplyTestable\WebClientBundle\Tests\Functional\Controller\Redirect\TestAction;

class MisinterpretedRequestWebsiteTest extends ActionTest
{
    /**
     * Sometimes the router gets things wrong and maps a task results
     * request to route app_test_redirector with the website parameter
     * as being the full request path and the test id missing
     */
    public function testWithMisinterpretedWebsite()
    {
        $website = 'http://example.com//123/456/results';
        $test_id = null;

        $this->performActionTest(array(
            'statusCode' => 302,
            'redirectPath' => '/website/http://example.com//test_id/123/task_id/456/results/'
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
