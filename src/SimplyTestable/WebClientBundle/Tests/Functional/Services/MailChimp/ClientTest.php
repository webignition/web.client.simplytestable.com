<?php

namespace SimplyTestable\WebClientBundle\Tests\Functional\Services\MailChimp;

use GuzzleHttp\Subscriber\Mock as HttpMockSubscriber;
use SimplyTestable\WebClientBundle\Exception\MailChimp\MemberExistsException;
use SimplyTestable\WebClientBundle\Exception\MailChimp\ResourceNotFoundException;
use SimplyTestable\WebClientBundle\Exception\MailChimp\UnknownException;
use SimplyTestable\WebClientBundle\Model\MailChimp\ApiError;
use SimplyTestable\WebClientBundle\Model\MailChimp\ListMembers;
use SimplyTestable\WebClientBundle\Services\MailChimp\Client as MailChimpClient;
use SimplyTestable\WebClientBundle\Tests\Factory\HttpResponseFactory;
use SimplyTestable\WebClientBundle\Tests\Functional\AbstractBaseTestCase;

class ClientTest extends AbstractBaseTestCase
{
    /**
     * @var MailChimpClient
     */
    private $mailChimpClient;

    /**
     * {@inheritdoc}
     */
    protected function setUp()
    {
        parent::setUp();

        $this->mailChimpClient = $this->container->get(MailChimpClient::class);
    }

    /**
     * @dataProvider getListMembersDataProvider
     *
     * @param array $httpFixtures
     * @param array $expectedMemberEmails
     */
    public function testGetListMembers(array $httpFixtures, array $expectedMemberEmails)
    {
        $this->setHttpFixtures($httpFixtures);

        $listMembers = $this->mailChimpClient->getListMembers(
            $this->container->getParameter('mailchimp_updates_list_id'),
            100,
            0
        );

        $this->assertInstanceOf(ListMembers::class, $listMembers);

        $this->assertEquals($expectedMemberEmails, $listMembers->getMemberEmails());
    }

    /**
     * @return array
     */
    public function getListMembersDataProvider()
    {
        return [
            'default' => [
                'httpFixtures' => [
                    HttpResponseFactory::createJsonResponse([
                        'members' => [
                            [
                                'email_address' => 'user@example.com',
                            ],
                        ],
                    ]),
                ],
                'expectedMemberEmails' => [
                    'user@example.com',
                ],
            ],
        ];
    }

    /**
     * @dataProvider addListMemberFailureDataProvider
     *
     * @param array $httpFixtures
     * @param string $expectedException
     * @param string $expectedExceptionMessage
     * @param int $expectedExceptionCode
     *
     * @throws MemberExistsException
     * @throws UnknownException
     */
    public function testAddListMemberFailure(
        array $httpFixtures,
        $expectedException,
        $expectedExceptionMessage,
        $expectedExceptionCode
    ) {
        $this->setHttpFixtures($httpFixtures);

        $this->expectException($expectedException);
        $this->expectExceptionMessage($expectedExceptionMessage);
        $this->expectExceptionCode($expectedExceptionCode);

        $this->mailChimpClient->addListMember(
            $this->container->getParameter('mailchimp_updates_list_id'),
            'user@example.com'
        );
    }

    /**
     * @return array
     */
    public function addListMemberFailureDataProvider()
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

    public function testAddListMemberSuccess()
    {
        $this->setHttpFixtures([
            HttpResponseFactory::createJsonResponse([])
        ]);

        $this->mailChimpClient->addListMember(
            $this->container->getParameter('mailchimp_updates_list_id'),
            'user@example.com'
        );
    }

    /**
     * @dataProvider removeListMemberFailureDataProvider
     *
     * @param array $httpFixtures
     * @param string $expectedException
     * @param string $expectedExceptionMessage
     * @param int $expectedExceptionCode
     *
     * @throws ResourceNotFoundException
     * @throws UnknownException
     */
    public function testRemoveListMemberFailure(
        array $httpFixtures,
        $expectedException,
        $expectedExceptionMessage,
        $expectedExceptionCode
    ) {
        $this->setHttpFixtures($httpFixtures);

        $this->expectException($expectedException);
        $this->expectExceptionMessage($expectedExceptionMessage);
        $this->expectExceptionCode($expectedExceptionCode);

        $this->mailChimpClient->removeListMember(
            $this->container->getParameter('mailchimp_updates_list_id'),
            'user@example.com'
        );
    }

    /**
     * @return array
     */
    public function removeListMemberFailureDataProvider()
    {
        return [
            'member not found' => [
                'httpFixtures' => [
                    HttpResponseFactory::createBadRequestResponse([], json_encode([
                        'title' => ApiError::TITLE_RESOURCE_NOT_FOUND,
                        'detail' => 'The requested resource could not be found.'
                    ])),
                ],
                'expectedException' => ResourceNotFoundException::class,
                'expectedExceptionMessage' => 'The requested resource could not be found.',
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

    public function testRemoveListMemberSuccess()
    {
        $this->setHttpFixtures([
            HttpResponseFactory::createSuccessResponse(),
        ]);

        $this->mailChimpClient->removeListMember(
            $this->container->getParameter('mailchimp_updates_list_id'),
            'user@example.com'
        );
    }

    /**
     * @param array $httpFixtures
     */
    private function setHttpFixtures($httpFixtures = [])
    {
        $mockSubscriber = new HttpMockSubscriber($httpFixtures);
        $this->mailChimpClient->getHttpClient()->getEmitter()->attach($mockSubscriber);
    }
}
