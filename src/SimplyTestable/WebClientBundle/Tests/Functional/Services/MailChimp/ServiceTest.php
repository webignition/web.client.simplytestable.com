<?php

namespace SimplyTestable\WebClientBundle\Tests\Functional\Services\MailChimp;

use Doctrine\ORM\EntityManagerInterface;
use Guzzle\Http\Message\Response;
use Guzzle\Plugin\Mock\MockPlugin;
use SimplyTestable\WebClientBundle\Entity\MailChimp\ListRecipients;
use SimplyTestable\WebClientBundle\Services\MailChimp\ListRecipientsService;
use SimplyTestable\WebClientBundle\Services\MailChimp\Service as MailChimpService;
use SimplyTestable\WebClientBundle\Tests\Factory\HttpResponseFactory;
use SimplyTestable\WebClientBundle\Tests\Functional\AbstractBaseTestCase;

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
     * {@inheritdoc}
     */
    protected function setUp()
    {
        parent::setUp();

        $this->mailChimpService = $this->container->get('simplytestable.services.mailchimpservice');
        $this->listRecipientsService = $this->container->get('simplytestable.services.mailchimp.listrecipients');
    }

    public function testSubscribeAlreadySubscribedLocally()
    {
        /* @var EntityManagerInterface $entityManager */
        $entityManager = $this->container->get('doctrine.orm.entity_manager');

        $listRecipients = new ListRecipients();
        $listRecipients->setListId($this->listRecipientsService->getListId(self::LIST_NAME));
        $listRecipients->addRecipient(self::USER_EMAIL);

        $entityManager->persist($listRecipients);
        $entityManager->flush();

        $result = $this->mailChimpService->subscribe(self::LIST_NAME, self::USER_EMAIL);

        $this->assertTrue($result);
    }

    public function testSubscribeSuccess()
    {
        $httpMockPlugin = new MockPlugin([
            Response::fromMessage('HTTP/1.1 200 OK')
        ]);

        $mailChimpClient = $this->container->get('simplytestable.services.mailchimp.client');
        $mailChimpClient->addSubscriber($httpMockPlugin);

        $result = $this->mailChimpService->subscribe(self::LIST_NAME, self::USER_EMAIL);

        $this->assertTrue($result);
    }

    public function testUnsubscribeNotAlreadySubscribedLocally()
    {
        $result = $this->mailChimpService->unsubscribe(self::LIST_NAME, self::USER_EMAIL);

        $this->assertTrue($result);
    }

    public function testUnsubscribeSuccess()
    {
        $httpMockPlugin = new MockPlugin([
            Response::fromMessage('HTTP/1.1 200 OK')
        ]);

        $mailChimpClient = $this->container->get('simplytestable.services.mailchimp.client');
        $mailChimpClient->addSubscriber($httpMockPlugin);

        /* @var EntityManagerInterface $entityManager */
        $entityManager = $this->container->get('doctrine.orm.entity_manager');

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
     * @param Response[] $httpFixtures
     * @param string[] $expectedResponseData
     */
    public function testRetrieveMembers($httpFixtures, $expectedResponseData)
    {
        $mockHttpPlugin = new MockPlugin($httpFixtures);

        $mailChimpClient = $this->container->get('simplytestable.services.mailchimp.client');
        $mailChimpClient->addSubscriber($mockHttpPlugin);

        $responseData = $this->mailChimpService->retrieveMembers(self::LIST_NAME);

        $this->assertEquals($expectedResponseData, $responseData);
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
                'expectedResponseData' => [
                    [
                        'email' => 'user@example.com',
                    ],
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
                'expectedResponseData' => [
                    [
                        'email' => 'user1@example.com',
                    ],
                    [
                        'email' => 'user2@example.com',
                    ],
                    [
                        'email' => 'user3@example.com',
                    ],
                    [
                        'email' => 'user4@example.com',
                    ],
                    [
                        'email' => 'user5@example.com',
                    ],
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
                'expectedResponseData' => [
                    [
                        'email' => 'user1@example.com',
                    ],
                    [
                        'email' => 'user2@example.com',
                    ],
                    [
                        'email' => 'user3@example.com',
                    ],
                    [
                        'email' => 'user4@example.com',
                    ],
                    [
                        'email' => 'user5@example.com',
                    ],
                ],
            ],
        ];
    }
}
