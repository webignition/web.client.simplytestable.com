<?php

namespace SimplyTestable\WebClientBundle\Tests\Functional\Services\MailChimp;

use Doctrine\ORM\EntityManagerInterface;
use Guzzle\Http\Message\Response;
use Guzzle\Plugin\Mock\MockPlugin;
use SimplyTestable\WebClientBundle\Entity\MailChimp\ListRecipients;
use SimplyTestable\WebClientBundle\Services\MailChimp\ListRecipientsService;
use SimplyTestable\WebClientBundle\Services\MailChimp\Service as MailChimpService;
use SimplyTestable\WebClientBundle\Tests\Functional\BaseSimplyTestableTestCase;

class ServiceTest extends BaseSimplyTestableTestCase
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
     * @param string[] $expectedMemberEmails
     */
    public function testRetrieveMembers($httpFixtures, $expectedMemberEmails)
    {
        $mockHttpPlugin = new MockPlugin($httpFixtures);

        $mailChimpClient = $this->container->get('simplytestable.services.mailchimp.client');
        $mailChimpClient->addSubscriber($mockHttpPlugin);

        $memberEmails = $this->mailChimpService->retrieveMembers(self::LIST_NAME);

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
                    $this->createGetListMembersHttpResponse(1, ['user@example.com']),
                ],
                'expectedMemberEmails' => ['user@example.com'],
            ],
            'many members in single response' => [
                'httpFixtures' => [
                    $this->createGetListMembersHttpResponse(5, [
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
                    $this->createGetListMembersHttpResponse(5, [
                        'user1@example.com',
                        'user2@example.com',
                    ]),
                    $this->createGetListMembersHttpResponse(5, [
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

    /**
     * @param int $total
     * @param string[] $memberEmails
     *
     * @return Response
     */
    private function createGetListMembersHttpResponse($total, $memberEmails)
    {
        $responseBody = json_encode([
            'total' => $total,
            'data' => $memberEmails,
        ]);

        return Response::fromMessage("HTTP/1.1 200 OK\nContent-type:application/json\n\n" . $responseBody);
    }
}
