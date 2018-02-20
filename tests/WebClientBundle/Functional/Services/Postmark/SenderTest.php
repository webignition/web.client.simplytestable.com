<?php

namespace Tests\WebClientBundle\Functional\Services\Postmark;

use SimplyTestable\WebClientBundle\Services\Postmark\Sender;
use Tests\WebClientBundle\Factory\MockFactory;
use Tests\WebClientBundle\Functional\AbstractBaseTestCase;
use MZ\PostmarkBundle\Postmark\Message as PostmarkMessage;
use SimplyTestable\WebClientBundle\Exception\Postmark\Response\Exception as PostmarkResponseException;
use SimplyTestable\WebClientBundle\Model\Postmark\Response as PostmarkResponse;

class SenderTest extends AbstractBaseTestCase
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

        $this->postmarkSender = $this->container->get('SimplyTestable\WebClientBundle\Services\Postmark\Sender');
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
        $this->expectException(PostmarkResponseException::class);
        $this->expectExceptionMessage($expectedExceptionMessage);
        $this->expectExceptionCode($expectedExceptionCode);

        $this->postmarkSender->send($message);
    }

    /**
     * @return array
     */
    public function sendPostmarkExceptionDataProvider()
    {
        return [
            'invalid to address' => [
                'message' => MockFactory::createPostmarkMessage([
                    'send' => [
                        'return' => json_encode([
                            'ErrorCode' => 300,
                            'Message' => "Invalid 'To' address: 'foo@example'.",
                        ]),
                    ]
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
                'message' => MockFactory::createPostmarkMessage([
                    'send' => [
                        'return' => json_encode($this->sendSuccessResponseData),
                    ],
                ]),
                'expectedExceptionMessage' => "Invalid 'To' address: 'foo@example'.",
                'expectedExceptionCode' => 300,
            ],
        ];
    }

    public function testGetLastMessageGetLastResponse()
    {
        $message = MockFactory::createPostmarkMessage([
            'send' => [
                'return' => json_encode($this->sendSuccessResponseData),
            ],
        ]);
        $response = $this->postmarkSender->send($message);

        $this->assertEquals($message, $this->postmarkSender->getLastMessage());
        $this->assertEquals($response, $this->postmarkSender->getLastResponse());
    }
}
