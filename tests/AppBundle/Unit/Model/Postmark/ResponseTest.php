<?php

namespace Tests\AppBundle\Unit\Model\Postmark;

use App\Model\Postmark\Response;

class ResponseTest extends \PHPUnit\Framework\TestCase
{
    public function testErrorResponse()
    {
        $response = new Response(json_encode([
            'ErrorCode' => 300,
            'MessageId' => '19cf44c7-f3a4-47a7-8680-47a8c5c5c5b1',
            'Message' => "Invalid 'To' address: 'foo@example",
        ]));

        $this->assertTrue($response->isError());
        $this->assertEquals(Response::ERROR_CODE_INVALID_TO_ADDRESS, $response->getErrorCode());
    }

    public function testSuccessResponse()
    {
        $response = new Response(json_encode([
            'To' => 'foo@example.com',
            'SubmittedAt' => '2014-02-20T05:51:07.4670731-05:00',
            'MessageId' => '19cf44c7-f3a4-47a7-8680-47a8c5c5c5b1',
            'ErrorCode' => 0,
            'Message' => 'OK',
        ]));

        $this->assertFalse($response->isError());
        $this->assertNull($response->getErrorCode());
        $this->assertEquals(Response::NON_ERROR_RESPONSE_MESSAGE, $response->getMessage());
    }
}
