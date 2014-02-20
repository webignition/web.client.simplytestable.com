<?php

namespace SimplyTestable\WebClientBundle\Tests\Model\Postmark\Response;

use SimplyTestable\WebClientBundle\Tests\BaseTestCase;
use SimplyTestable\WebClientBundle\Model\Postmark\Response as PostmarkResponse;

abstract class ResponseTest extends BaseTestCase {   
    
    /**
     *
     * @var \SimplyTestable\WebClientBundle\Model\Postmark\Response
     */
    protected $response;
    
    public function setUp() {
        $this->response = new PostmarkResponse($this->getJsonResponse());
    }
    
    abstract protected function getJsonResponse();    
}