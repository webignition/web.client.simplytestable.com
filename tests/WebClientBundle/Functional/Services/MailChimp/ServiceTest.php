<?php

namespace Tests\WebClientBundle\Functional\Services\MailChimp;

use Doctrine\ORM\EntityManagerInterface;
use SimplyTestable\WebClientBundle\Entity\MailChimp\ListRecipients;
use SimplyTestable\WebClientBundle\Exception\MailChimp\MemberExistsException;
use SimplyTestable\WebClientBundle\Exception\MailChimp\ResourceNotFoundException;
use SimplyTestable\WebClientBundle\Exception\MailChimp\UnknownException;
use SimplyTestable\WebClientBundle\Model\MailChimp\ApiError;
use SimplyTestable\WebClientBundle\Services\MailChimp\ListRecipientsService;
use SimplyTestable\WebClientBundle\Services\MailChimp\Service as MailChimpService;
use Tests\WebClientBundle\Factory\HttpResponseFactory;
use Tests\WebClientBundle\Functional\AbstractBaseTestCase;
use Tests\WebClientBundle\Services\HttpMockHandler;

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
        $entityManager = self::$container->get('doctrine.orm.entity_manager');

        $listRecipients = new ListRecipients();
        $listRecipients->setListId($this->listRecipientsService->getListId(self::LIST_NAME));
        $listRecipients->addRecipient(self::USER_EMAIL);

        $entityManager->persist($listRecipients);
        $entityManager->flush();

        $result = $this->mailChimpService->subscribe(self::LIST_NAME, self::USER_EMAIL);

        $this->assertTrue($result);
    }

    /**
     * @dataProvider subscribeFailureDataProvider
     *
     * @param array $httpFixtures
     * @param string $expectedException
     * @param string $expectedExceptionMessage
     * @param int $expectedExceptionCode
     *
     * @throws MemberExistsException
     * @throws UnknownException
     */
    public function testSubscribeFailure(
        array $httpFixtures,
        $expectedException,
        $expectedExceptionMessage,
        $expectedExceptionCode
    ) {
        $this->httpMockHandler->appendFixtures($httpFixtures);

        $this->expectException($expectedException);
        $this->expectExceptionMessage($expectedExceptionMessage);
        $this->expectExceptionCode($expectedExceptionCode);

        $this->mailChimpService->subscribe(self::LIST_NAME, self::USER_EMAIL);
    }

    /**
     * @return array
     */
    public function subscribeFailureDataProvider()
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

        $result = $this->mailChimpService->subscribe(self::LIST_NAME, self::USER_EMAIL);

        $this->assertTrue($result);
    }

    public function testUnsubscribeNotAlreadySubscribedLocally()
    {
        $result = $this->mailChimpService->unsubscribe(self::LIST_NAME, self::USER_EMAIL);

        $this->assertTrue($result);
    }

    /**
     * @dataProvider unsubscribeFailureDataProvider
     *
     * @param array $httpFixtures
     * @param string $expectedException
     * @param string $expectedExceptionMessage
     * @param int $expectedExceptionCode
     *
     * @throws ResourceNotFoundException
     * @throws UnknownException
     */
    public function testUnsubscribeFailure(
        array $httpFixtures,
        $expectedException,
        $expectedExceptionMessage,
        $expectedExceptionCode
    ) {
        $this->httpMockHandler->appendFixtures($httpFixtures);

        /* @var EntityManagerInterface $entityManager */
        $entityManager = self::$container->get('doctrine.orm.entity_manager');

        $listRecipients = new ListRecipients();
        $listRecipients->setListId($this->listRecipientsService->getListId(self::LIST_NAME));
        $listRecipients->addRecipient(self::USER_EMAIL);

        $entityManager->persist($listRecipients);
        $entityManager->flush();

        $this->expectException($expectedException);
        $this->expectExceptionMessage($expectedExceptionMessage);
        $this->expectExceptionCode($expectedExceptionCode);

        $this->mailChimpService->unsubscribe(self::LIST_NAME, self::USER_EMAIL);
    }

    /**
     * @return array
     */
    public function unsubscribeFailureDataProvider()
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
        $entityManager = self::$container->get('doctrine.orm.entity_manager');

        $listRecipients = new ListRecipients();
        $listRecipients->setListId($this->listRecipientsService->getListId(self::LIST_NAME));
        $listRecipients->addRecipient(self::USER_EMAIL);

        $entityManager->persist($listRecipients);
        $entityManager->flush();

        $result = $this->mailChimpService->unsubscribe(self::LIST_NAME, self::USER_EMAIL);

        $this->assertTrue($result);
    }

    /**
     * @dataProvider retrieveMembersDataProvider
     *
     * @param array[] $httpFixtures
     * @param string[] $expectedMemberEmails
     */
    public function testRetrieveMembers($httpFixtures, $expectedMemberEmails)
    {
        $this->httpMockHandler->appendFixtures($httpFixtures);

        $memberEmails = $this->mailChimpService->retrieveMemberEmails(self::LIST_NAME);

        $this->assertEquals($expectedMemberEmails, $memberEmails);
    }

    /**
     * @return array
     */
    public function retrieveMembersDataProvider()
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
