<?php

namespace SimplyTestable\WebClientBundle\Tests\Functional\Services\Postmark;

use Mockery\Mock;
use SimplyTestable\WebClientBundle\Services\Postmark\Sender;
use SimplyTestable\WebClientBundle\Tests\Functional\BaseSimplyTestableTestCase;
use MZ\PostmarkBundle\Postmark\Message as PostmarkMessage;
use SimplyTestable\WebClientBundle\Exception\Postmark\Response\Exception as PostmarkResponseException;
use SimplyTestable\WebClientBundle\Model\Postmark\Response as PostmarkResponse;

class SenderTest extends BaseSimplyTestableTestCase
{
    /**
     * @var array
     */
    private $sendSuccessResponseData = [
        'ErrorCode' => 0,
        'Message' => 'OK',
        'To' => 'foo@example.com',
        'SubmittedAt' => '2014-02-20T05:51:07.4670731-05:00',
        'MessageID' => '19cf44c7-f3a4-47a7-8680-47a8c5c5c5b1',
    ];

    /**
     * @var Sender
     */
    private $postmarkSender;

    /**
     * {@inheritdoc}
     */
    protected function setUp()
    {
        parent::setUp();

        $this->postmarkSender = $this->container->get('simplytestable.services.postmark.sender');
    }

    /**
     * @dataProvider sendPostmarkExceptionDataProvider
     *
     * @param PostmarkMessage $message
     * @param string $expectedExceptionMessage
     * @param int $expectedExceptionCode
     *
     * @throws PostmarkResponseException
     */
    public function testSendPostmarkException(
        PostmarkMessage $message,
        $expectedExceptionMessage,
        $expectedExceptionCode
    ) {
        $this->setExpectedException(
            PostmarkResponseException::class,
            $expectedExceptionMessage,
            $expectedExceptionCode
        );

        $this->postmarkSender->send($message);
    }

    /**
     * @return array
     */
    public function sendPostmarkExceptionDataProvider()
    {
        return [
            'invalid to address' => [
                'message' => $this->createPostmarkMessage([
                    'ErrorCode' => 300,
                    'Message' => "Invalid 'To' address: 'foo@example'.",
                ]),
                'expectedExceptionMessage' => "Invalid 'To' address: 'foo@example'.",
                'expectedExceptionCode' => 300,
            ],
        ];
    }

    /**
     * @dataProvider sendSuccessDataProvider
     *
     * @param PostmarkMessage $message
     *
     * @throws PostmarkResponseException
     */
    public function testSendSuccess(
        PostmarkMessage $message
    ) {
        $response = $this->postmarkSender->send($message);

        $this->assertInstanceOf(PostmarkResponse::class, $response);
        $this->assertEquals('OK', $response->getMessage());
        $this->assertEquals(0, $response->getErrorCode());
    }

    /**
     * @return array
     */
    public function sendSuccessDataProvider()
    {
        return [
            'success' => [
                'message' => $this->createPostmarkMessage($this->sendSuccessResponseData),
                'expectedExceptionMessage' => "Invalid 'To' address: 'foo@example'.",
                'expectedExceptionCode' => 300,
            ],
        ];
    }

    public function testGetLastMessageGetLastResponse()
    {
        $message = $this->createPostmarkMessage($this->sendSuccessResponseData);
        $response = $this->postmarkSender->send($message);

        $this->assertEquals($message, $this->postmarkSender->getLastMessage());
        $this->assertEquals($response, $this->postmarkSender->getLastResponse());
    }

    /**
     * @param array $responseData
     *
     * @return Mock|PostmarkMessage
     */
    private function createPostmarkMessage(array $responseData)
    {
        /* @var PostmarkMessage|Mock $message */
        $message = \Mockery::mock(PostmarkMessage::class);
        $message
            ->shouldReceive('send')
            ->andReturn(json_encode($responseData));

        return $message;
    }
}
