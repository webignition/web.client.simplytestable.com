<?php

namespace SimplyTestable\WebClientBundle\Tests\Controller\TestStart\StartNewAction;

use SimplyTestable\WebClientBundle\Tests\Controller\TestStart\ActionTest as BaseActionTest;

class ActionTest extends BaseActionTest {      
    
    protected function getActionName() {
        return 'startNewAction';
    }  
    
    public function testStartActionWithNoWebsite() {          
        $this->performActionTest(array(
            'statusCode' => 302,
            'redirectPath' => '/',
            'flash' => array(
                'test_start_error' => 'website-blank'
            )
        ));
    }    
    
    public function testStartActionWithNoTestTypes() {          
        $this->performActionTest(array(
            'statusCode' => 302,
            'redirectPath' => '/',
            'flash' => array(
                'test_start_error' => 'no-test-types-selected'
            )
        ), array(
            'postData' => array(
                'website' => 'http://example.com'
            )
        ));        
    }     
    
    public function testStartActionWithWebsiteAndTestType() {          
        $this->setHttpFixtures($this->getHttpFixtures($this->getFixturesDataPath(__FUNCTION__ . '/HttpResponses')));
        
        $this->performActionTest(array(
            'statusCode' => 302,
            'redirectPath' => '/http://example.com//1/progress/'
        ), array(
            'postData' => array(
                'website' => 'http://example.com',
                'html-validation' => '1'
            )
        ));          
    }     
    
    public function testStartActionReceives503FromCoreApplication() {          
        $this->setHttpFixtures($this->getHttpFixtures($this->getFixturesDataPath(__FUNCTION__ . '/HttpResponses')));
        
        $this->performActionTest(array(
            'statusCode' => 302,
            'redirectPath' => '/http://example.com//'
        ), array(
            'postData' => array(
                'website' => 'http://example.com',
                'html-validation' => '1'
            )
        ));         
    }    
    
    public function testStartActionReceives404FromCoreApplication() {          
        $this->setHttpFixtures($this->getHttpFixtures($this->getFixturesDataPath(__FUNCTION__ . '/HttpResponses')));
        
        $this->performActionTest(array(
            'statusCode' => 302,
            'redirectPath' => '/http://example.com//'
        ), array(
            'postData' => array(
                'website' => 'http://example.com',
                'html-validation' => '1'
            )
        ));
    }     
    
    public function testStartActionReceives500FromCoreApplication() {          
        $this->setHttpFixtures($this->getHttpFixtures($this->getFixturesDataPath(__FUNCTION__ . '/HttpResponses')));
        
        $this->performActionTest(array(
            'statusCode' => 302,
            'redirectPath' => '/http://example.com//'
        ), array(
            'postData' => array(
                'website' => 'http://example.com',
                'html-validation' => '1'
            )
        ));
    }
    
    public function testStartRaisesCurlErrorCommunicatingWithCoreApplication() {       
        $this->getWebResourceService()->setRequestSkeletonToCurlErrorMap(array(
            'http://ci.app.simplytestable.com/job/http%3A%2F%2Fexample.com%2F/start/?type=full%20site&test-types%5B0%5D=HTML%20validation&test-type-options%5BJS%2Bstatic%2Banalysis%5D%5Bjslint-option-bitwise%5D=1&test-type-options%5BJS%2Bstatic%2Banalysis%5D%5Bjslint-option-continue%5D=1&test-type-options%5BJS%2Bstatic%2Banalysis%5D%5Bjslint-option-debug%5D=1&test-type-options%5BJS%2Bstatic%2Banalysis%5D%5Bjslint-option-evil%5D=1&test-type-options%5BJS%2Bstatic%2Banalysis%5D%5Bjslint-option-eqeq%5D=1&test-type-options%5BJS%2Bstatic%2Banalysis%5D%5Bjslint-option-es5%5D=1&test-type-options%5BJS%2Bstatic%2Banalysis%5D%5Bjslint-option-forin%5D=1&test-type-options%5BJS%2Bstatic%2Banalysis%5D%5Bjslint-option-newcap%5D=1&test-type-options%5BJS%2Bstatic%2Banalysis%5D%5Bjslint-option-nomen%5D=1&test-type-options%5BJS%2Bstatic%2Banalysis%5D%5Bjslint-option-plusplus%5D=1&test-type-options%5BJS%2Bstatic%2Banalysis%5D%5Bjslint-option-regexp%5D=1&test-type-options%5BJS%2Bstatic%2Banalysis%5D%5Bjslint-option-undef%5D=1&test-type-options%5BJS%2Bstatic%2Banalysis%5D%5Bjslint-option-unparam%5D=1&test-type-options%5BJS%2Bstatic%2Banalysis%5D%5Bjslint-option-sloppy%5D=1&test-type-options%5BJS%2Bstatic%2Banalysis%5D%5Bjslint-option-stupid%5D=1&test-type-options%5BJS%2Bstatic%2Banalysis%5D%5Bjslint-option-sub%5D=1&test-type-options%5BJS%2Bstatic%2Banalysis%5D%5Bjslint-option-vars%5D=1&test-type-options%5BJS%2Bstatic%2Banalysis%5D%5Bjslint-option-white%5D=1&test-type-options%5BJS%2Bstatic%2Banalysis%5D%5Bjslint-option-anon%5D=1' => array(
                'GET' => array(
                    'errorMessage' => "Couldn't resolve host. The given remote host was not resolved.",
                    'errorNumber' => 6                    
                )
            )
        ));
        
        $this->performActionTest(array(
            'statusCode' => 302,
            'redirectPath' => '/',
            'flash' => array(
                'test_start_error' => 'curl-error',
                'curl_error_code' => 6
            )            
        ), array(
            'postData' => array(
                'website' => 'http://example.com',
                'html-validation' => '1'
            )
        ));
    }


}


