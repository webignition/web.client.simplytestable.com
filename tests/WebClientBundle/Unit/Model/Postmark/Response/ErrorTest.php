<?php

namespace Tests\WebClientBundle\Unit\Model\Postmark\Response;

use SimplyTestable\WebClientBundle\Model\Postmark\Response as PostmarkResponse;

class ErrorTest extends ResponseTest
{
    protected function getJsonResponse()
    {
        return '{"ErrorCode":300,"Message":"Invalid \'To\' address: \'foo@example\'."}';
    }

    public function testIsError()
    {
        $this->assertTrue($this->response->isError());
    }

    public function testErrorCodeIsCorrect()
    {
        $this->assertEquals(PostmarkResponse::ERROR_CODE_INVALID_TO_ADDRESS, $this->response->getErrorCode());
    }
}
