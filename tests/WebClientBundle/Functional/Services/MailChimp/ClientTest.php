<?php

namespace Tests\WebClientBundle\Functional\Services\MailChimp;

use SimplyTestable\WebClientBundle\Exception\MailChimp\MemberExistsException;
use SimplyTestable\WebClientBundle\Exception\MailChimp\ResourceNotFoundException;
use SimplyTestable\WebClientBundle\Exception\MailChimp\UnknownException;
use SimplyTestable\WebClientBundle\Model\MailChimp\ApiError;
use SimplyTestable\WebClientBundle\Model\MailChimp\ListMembers;
use SimplyTestable\WebClientBundle\Services\MailChimp\Client as MailChimpClient;
use Tests\WebClientBundle\Factory\HttpResponseFactory;
use Tests\WebClientBundle\Functional\AbstractBaseTestCase;
use Tests\WebClientBundle\Services\HttpMockHandler;
use webignition\HttpHistoryContainer\Container as HttpHistoryContainer;

class ClientTest extends AbstractBaseTestCase
{
    /**
     * @var MailChimpClient
     */
    private $mailChimpClient;

    /**
     * @var HttpHistoryContainer
     */
    private $httpHistory;

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

        $this->mailChimpClient = $this->container->get(MailChimpClient::class);
        $this->httpHistory = $this->container->get(HttpHistoryContainer::class);
        $this->httpMockHandler = $this->container->get(HttpMockHandler::class);
    }

    /**
     * @dataProvider getListMembersDataProvider
     *
     * @param array $httpFixtures
     * @param array $expectedMemberEmails
     */
    public function testGetListMembers(array $httpFixtures, array $expectedMemberEmails)
    {
        $this->httpMockHandler->appendFixtures($httpFixtures);

        $listMembers = $this->mailChimpClient->getListMembers(
            $this->container->getParameter('mailchimp_updates_list_id'),
            100,
            0
        );

        $this->assertInstanceOf(ListMembers::class, $listMembers);
        $this->assertEquals($expectedMemberEmails, $listMembers->getMemberEmails());
        $this->assertLastRequestAuthorizationHeader();
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
        $this->httpMockHandler->appendFixtures($httpFixtures);

        $this->expectException($expectedException);
        $this->expectExceptionMessage($expectedExceptionMessage);
        $this->expectExceptionCode($expectedExceptionCode);

        $this->mailChimpClient->addListMember(
            $this->container->getParameter('mailchimp_updates_list_id'),
            'user@example.com'
        );

        $this->assertLastRequestAuthorizationHeader();
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
        $this->httpMockHandler->appendFixtures([
            HttpResponseFactory::createJsonResponse([])
        ]);

        $this->mailChimpClient->addListMember(
            $this->container->getParameter('mailchimp_updates_list_id'),
            'user@example.com'
        );

        $this->assertLastRequestAuthorizationHeader();
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
        $this->httpMockHandler->appendFixtures($httpFixtures);

        $this->expectException($expectedException);
        $this->expectExceptionMessage($expectedExceptionMessage);
        $this->expectExceptionCode($expectedExceptionCode);

        $this->mailChimpClient->removeListMember(
            $this->container->getParameter('mailchimp_updates_list_id'),
            'user@example.com'
        );

        $this->assertLastRequestAuthorizationHeader();
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
        $this->httpMockHandler->appendFixtures([
            HttpResponseFactory::createSuccessResponse(),
        ]);

        $this->mailChimpClient->removeListMember(
            $this->container->getParameter('mailchimp_updates_list_id'),
            'user@example.com'
        );

        $this->assertLastRequestAuthorizationHeader();
    }

    private function assertLastRequestAuthorizationHeader()
    {
        $authorizationHeader = $this->httpHistory->getLastRequest()->getHeaderLine('authorization');
        $decodedAuthorizationHeader = base64_decode(str_replace('Basic ', '', $authorizationHeader));
        $authorizationHeaderParts = explode(':', $decodedAuthorizationHeader);

        $this->assertEquals($this->container->getParameter('mailchimp_api_key'), $authorizationHeaderParts[1]);
    }
}
