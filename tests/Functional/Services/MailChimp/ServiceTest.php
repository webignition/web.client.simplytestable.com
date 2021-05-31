<?php
/** @noinspection PhpDocSignatureInspection */

namespace App\Tests\Functional\Services\MailChimp;

use Doctrine\ORM\EntityManagerInterface;
use App\Entity\MailChimp\ListRecipients;
use App\Exception\MailChimp\MemberExistsException;
use App\Exception\MailChimp\ResourceNotFoundException;
use App\Exception\MailChimp\UnknownException;
use App\Model\MailChimp\ApiError;
use App\Services\MailChimp\ListRecipientsService;
use App\Services\MailChimp\Service as MailChimpService;
use App\Tests\Factory\HttpResponseFactory;
use App\Tests\Functional\AbstractBaseTestCase;
use App\Tests\Services\HttpMockHandler;

class ServiceTest extends AbstractBaseTestCase
{
    const LIST_NAME = 'updates';
    const USER_EMAIL = 'user@example.com';

    /**
     * @var MailChimpService
     */
    private $mailChimpService;

    /**
     * @var ListRecipientsService
     */
    private $listRecipientsService;

    /**
     * @var HttpMockHandler
     */
    private $httpMockHandler;

    /**
     * {@inheritdoc}
     */
    protected function setUp()
    {
        parent::setUp();

        $this->mailChimpService = self::$container->get(MailChimpService::class);
        $this->listRecipientsService = self::$container->get(ListRecipientsService::class);
        $this->httpMockHandler = self::$container->get(HttpMockHandler::class);
    }

    public function testSubscribeAlreadySubscribedLocally()
    {
        /* @var EntityManagerInterface $entityManager */
        $entityManager = self::$container->get(EntityManagerInterface::class);

        $listRecipients = ListRecipients::create(
            $this->listRecipientsService->getListId(self::LIST_NAME),
            [self::USER_EMAIL]
        );

        $entityManager->persist($listRecipients);
        $entityManager->flush();

        $this->mailChimpService->subscribe(self::LIST_NAME, self::USER_EMAIL);
        $this->assertTrue(true);
    }

    /**
     * @dataProvider subscribeFailureDataProvider
     */
    public function testSubscribeFailure(
        array $httpFixtures,
        string $expectedException,
        string $expectedExceptionMessage,
        int $expectedExceptionCode
    ) {
        $this->httpMockHandler->appendFixtures($httpFixtures);

        $this->expectException($expectedException);
        $this->expectExceptionMessage($expectedExceptionMessage);
        $this->expectExceptionCode($expectedExceptionCode);

        $this->mailChimpService->subscribe(self::LIST_NAME, self::USER_EMAIL);
    }

    public function subscribeFailureDataProvider(): array
    {
        return [
            'member exists' => [
                'httpFixtures' => [
                    HttpResponseFactory::createBadRequestResponse([], json_encode([
                        'title' => ApiError::TITLE_MEMBER_EXISTS,
                        'detail' => 'user@example.com is already a list member.'
                    ])),
                ],
                'expectedException' => MemberExistsException::class,
                'expectedExceptionMessage' => 'user@example.com is already a list member.',
                'expectedExceptionCode' => 0,
            ],
            'unknown error' => [
                'httpFixtures' => [
                    HttpResponseFactory::createBadRequestResponse([], json_encode([
                        'title' => 'foo',
                        'detail' => 'foo'
                    ])),
                ],
                'expectedException' => UnknownException::class,
                'expectedExceptionMessage' => 'foo',
                'expectedExceptionCode' => 0,
            ],
        ];
    }

    public function testSubscribeSuccess()
    {
        $this->httpMockHandler->appendFixtures([
            HttpResponseFactory::createSuccessResponse(),
        ]);

        $this->mailChimpService->subscribe(self::LIST_NAME, self::USER_EMAIL);
        $this->assertTrue(true);
    }

    public function testUnsubscribeNotAlreadySubscribedLocally()
    {
        $this->mailChimpService->unsubscribe(self::LIST_NAME, self::USER_EMAIL);
        $this->assertTrue(true);
    }

    /**
     * @dataProvider unsubscribeFailureDataProvider
     */
    public function testUnsubscribeFailure(
        array $httpFixtures,
        string $expectedException,
        string $expectedExceptionMessage,
        int $expectedExceptionCode
    ) {
        $this->httpMockHandler->appendFixtures($httpFixtures);

        /* @var EntityManagerInterface $entityManager */
        $entityManager = self::$container->get(EntityManagerInterface::class);

        $listRecipients = ListRecipients::create(
            $this->listRecipientsService->getListId(self::LIST_NAME),
            [self::USER_EMAIL]
        );

        $entityManager->persist($listRecipients);
        $entityManager->flush();

        $this->expectException($expectedException);
        $this->expectExceptionMessage($expectedExceptionMessage);
        $this->expectExceptionCode($expectedExceptionCode);

        $this->mailChimpService->unsubscribe(self::LIST_NAME, self::USER_EMAIL);
    }

    public function unsubscribeFailureDataProvider(): array
    {
        return [
            'not found' => [
                'httpFixtures' => [
                    HttpResponseFactory::createNotFoundResponse([], json_encode([
                        'title' => ApiError::TITLE_RESOURCE_NOT_FOUND,
                        'detail' => ''
                    ])),
                ],
                'expectedException' => ResourceNotFoundException::class,
                'expectedExceptionMessage' => '',
                'expectedExceptionCode' => 0,
            ],
            'unknown error' => [
                'httpFixtures' => [
                    HttpResponseFactory::createBadRequestResponse([], json_encode([
                        'title' => 'foo',
                        'detail' => 'foo'
                    ])),
                ],
                'expectedException' => UnknownException::class,
                'expectedExceptionMessage' => 'foo',
                'expectedExceptionCode' => 0,
            ],
        ];
    }

    public function testUnsubscribeSuccess()
    {
        $this->httpMockHandler->appendFixtures([
            HttpResponseFactory::createSuccessResponse(),
        ]);

        /* @var EntityManagerInterface $entityManager */
        $entityManager = self::$container->get(EntityManagerInterface::class);

        $listRecipients = ListRecipients::create(
            $this->listRecipientsService->getListId(self::LIST_NAME),
            [self::USER_EMAIL]
        );
        $entityManager->persist($listRecipients);
        $entityManager->flush();

        $this->mailChimpService->unsubscribe(self::LIST_NAME, self::USER_EMAIL);
        $this->assertTrue(true);
    }

    /**
     * @dataProvider retrieveMembersDataProvider
     */
    public function testRetrieveMembers(array $httpFixtures, array $expectedMemberEmails)
    {
        $this->httpMockHandler->appendFixtures($httpFixtures);

        $memberEmails = $this->mailChimpService->retrieveMemberEmails(self::LIST_NAME);

        $this->assertEquals($expectedMemberEmails, $memberEmails);
    }

    public function retrieveMembersDataProvider(): array
    {
        return [
            'single member in single response' => [
                'httpFixtures' => [
                    HttpResponseFactory::createMailChimpListMembersResponse(1, ['user@example.com']),
                ],
                'expectedMemberEmails' => [
                    'user@example.com',
                ],
            ],
            'many members in single response' => [
                'httpFixtures' => [
                    HttpResponseFactory::createMailChimpListMembersResponse(5, [
                        'user1@example.com',
                        'user2@example.com',
                        'user3@example.com',
                        'user4@example.com',
                        'user5@example.com',
                    ]),
                ],
                'expectedMemberEmails' => [
                    'user1@example.com',
                    'user2@example.com',
                    'user3@example.com',
                    'user4@example.com',
                    'user5@example.com',
                ],
            ],
            'many members in many responses' => [
                'httpFixtures' => [
                    HttpResponseFactory::createMailChimpListMembersResponse(5, [
                        'user1@example.com',
                        'user2@example.com',
                    ]),
                    HttpResponseFactory::createMailChimpListMembersResponse(5, [
                        'user3@example.com',
                        'user4@example.com',
                        'user5@example.com',
                    ]),
                ],
                'expectedMemberEmails' => [
                    'user1@example.com',
                    'user2@example.com',
                    'user3@example.com',
                    'user4@example.com',
                    'user5@example.com',
                ],
            ],
        ];
    }
}
