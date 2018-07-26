<?php

namespace App\Tests\Functional\Services;

use App\Tests\Factory\PostmarkHttpResponseFactory;
use App\Tests\Functional\AbstractBaseTestCase;
use App\Tests\Services\HttpMockHandler;
use Postmark\Models\PostmarkException;
use Postmark\PostmarkClient;
use Psr\Http\Message\ResponseInterface;
use webignition\HttpHistoryContainer\Container as HttpHistoryContainer;

class PostmarkClientTest extends AbstractBaseTestCase
{
    public function testSendEmailSuccess()
    {
        $postmarkClient = self::$container->get(PostmarkClient::class);
        $httpMockHandler = self::$container->get(HttpMockHandler::class);
        $httpHistoryContainer = self::$container->get(HttpHistoryContainer::class);

        $httpMockHandler->appendFixtures([
            PostmarkHttpResponseFactory::createSuccessResponse(),
        ]);

        $from = 'sender@example.com';
        $to = 'recipient@example.com';
        $subject = 'Message Subject';
        $textBody = 'Message Body';

        $postmarkClient->sendEmail($from, $to, $subject, '', $textBody);

        $lastRequest = $httpHistoryContainer->getLastRequest();

        $this->assertEquals(
            [
                'From' => $from,
                'To' => $to,
                'Subject' => $subject,
                'HtmlBody' => '',
                'TextBody' => $textBody,
                'TrackOpens' => true,
            ],
            json_decode($lastRequest->getBody()->getContents(), true)
        );
    }

    /**
     * @dataProvider sendEmailFailureDataProvider
     *
     * @param ResponseInterface $postmarkResponse
     * @param int $expectedPostmarkApiErrorCode
     */
    public function testSendEmailFailure(ResponseInterface $postmarkResponse, int $expectedPostmarkApiErrorCode)
    {
        $postmarkClient = self::$container->get(PostmarkClient::class);
        $httpMockHandler = self::$container->get(HttpMockHandler::class);

        $httpMockHandler->appendFixtures([$postmarkResponse]);

        $from = 'sender@example.com';
        $to = 'recipient@example.com';
        $subject = 'Message Subject';
        $textBody = 'Message Body';

        try {
            $postmarkClient->sendEmail($from, $to, $subject, '', $textBody);
        } catch (PostmarkException $postmarkException) {
            $this->assertEquals($expectedPostmarkApiErrorCode, $postmarkException->postmarkApiErrorCode);
        }
    }

    /**
     * @return array
     */
    public function sendEmailFailureDataProvider()
    {
        return [
            'not allowed to send' => [
                'postmarkResponse' => PostmarkHttpResponseFactory::createErrorResponse(405),
                'expectedPostmarkApiErrorCode' => 405,
            ],
            'inactive recipient' => [
                'postmarkResponse' => PostmarkHttpResponseFactory::createErrorResponse(406),
                'expectedPostmarkApiErrorCode' => 406,
            ],
        ];
    }

    protected function tearDown()
    {
        parent::tearDown();

        \Mockery::close();
    }
}
