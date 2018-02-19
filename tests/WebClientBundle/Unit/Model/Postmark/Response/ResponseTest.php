<?php

namespace Tests\WebClientBundle\Unit\Model\Postmark\Response;

use SimplyTestable\WebClientBundle\Model\Postmark\Response;
use SimplyTestable\WebClientBundle\Model\Postmark\Response as PostmarkResponse;

abstract class ResponseTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var Response
     */
    protected $response;

    protected function setUp()
    {
        $this->response = new PostmarkResponse($this->getJsonResponse());
    }

    abstract protected function getJsonResponse();
}
