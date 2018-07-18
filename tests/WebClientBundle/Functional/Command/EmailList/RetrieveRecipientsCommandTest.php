<?php

namespace Tests\WebClientBundle\Functional\Command\EmailList;

use SimplyTestable\WebClientBundle\Command\EmailList\RetrieveRecipientsCommand;
use SimplyTestable\WebClientBundle\Entity\MailChimp\ListRecipients;
use SimplyTestable\WebClientBundle\Services\MailChimp\ListRecipientsService;
use Tests\WebClientBundle\Factory\HttpResponseFactory;
use Tests\WebClientBundle\Functional\AbstractBaseTestCase;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Output\NullOutput;
use Tests\WebClientBundle\Services\HttpMockHandler;

class RetrieveRecipientsCommandTest extends AbstractBaseTestCase
{
    /**
     * @var RetrieveRecipientsCommand
     */
    protected $retrieveRecipientsCommand;

    /**
     * {@inheritdoc}
     */
    protected function setUp()
    {
        parent::setUp();

        $this->retrieveRecipientsCommand = self::$container->get(RetrieveRecipientsCommand::class);
    }

    /**
     * @dataProvider runDataProvider
     *
     * @param array $httpFixtures
     * @param string $listName
     * @param bool $expectedHasList
     * @param int $expectedReturnValue
     * @param string[] $expectedRetrievedEmails
     *
     * @throws \Exception
     */
    public function testRun(
        array $httpFixtures,
        $listName,
        $expectedHasList,
        $expectedReturnValue,
        array $expectedRetrievedEmails
    ) {
        $entityManager = self::$container->get('doctrine.orm.entity_manager');
        $listRecipientsService = self::$container->get(ListRecipientsService::class);
        $httpMockHandler = self::$container->get(HttpMockHandler::class);

        $httpMockHandler->appendFixtures($httpFixtures);

        $input = new ArrayInput([
            RetrieveRecipientsCommand::ARG_LIST_NAME => $listName,
        ]);

        $returnValue = $this->retrieveRecipientsCommand->run($input, new NullOutput());

        $this->assertEquals($expectedReturnValue, $returnValue);

        if ($expectedHasList) {
            $listId = $listRecipientsService->getListId($listName);

            $listRecipientsRepository = $entityManager->getRepository(ListRecipients::class);

            /* @var ListRecipients $listRecipients */
            $listRecipients = $listRecipientsRepository->findOneBy([
                'listId' => $listId,
            ]);

            $this->assertEquals($expectedRetrievedEmails, $listRecipients->getRecipients());
        }
    }

    /**
     * @return array
     */
    public function runDataProvider()
    {
        return [
            'invalid list name' => [
                'httpFixtures' => [],
                'listName' => 'foo',
                'expectedHasList' => false,
                'expectedReturnValue' => 0,
                'expectedRetrievedEmails' => [],
            ],
            'valid list name' => [
                'httpFixtures' => [
                    HttpResponseFactory::createMailChimpListMembersResponse(1, ['user@example.com']),
                ],
                'listName' => 'announcements',
                'expectedHasList' => true,
                'expectedReturnValue' => 0,
                'expectedRetrievedEmails' => [
                    'user@example.com',
                ],
            ],
        ];
    }
}
