<?php

namespace App\Tests\Functional\Command\EmailList;

use App\Tests\Services\ObjectReflector;
use Doctrine\ORM\EntityManagerInterface;
use App\Command\EmailList\RetrieveAllRecipientsCommand;
use App\Entity\MailChimp\ListRecipients;
use App\Services\MailChimp\ListRecipientsService;
use App\Tests\Factory\HttpResponseFactory;
use App\Tests\Functional\AbstractBaseTestCase;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Output\NullOutput;
use App\Tests\Services\HttpMockHandler;

class RetrieveAllRecipientsCommandTest extends AbstractBaseTestCase
{
    /**
     * @var RetrieveAllRecipientsCommand
     */
    private $retrieveAllRecipientsCommand;

    /**
     * {@inheritdoc}
     */
    protected function setUp()
    {
        parent::setUp();

        $this->retrieveAllRecipientsCommand = self::$container->get(RetrieveAllRecipientsCommand::class);
    }

    public function testRun()
    {
        $entityManager = self::$container->get(EntityManagerInterface::class);
        $listRecipientsService = self::$container->get(ListRecipientsService::class);
        $httpMockHandler = self::$container->get(HttpMockHandler::class);

        $listNames = $listRecipientsService->getListNames();
        $httpFixtures = [];
        $expectedRetrievedEmails = [];

        foreach ($listNames as $listName) {
            $listRecipient = $listName . '-user@example.com';
            $httpFixtures[] = HttpResponseFactory::createMailChimpListMembersResponse(1, [$listRecipient]);
            $expectedRetrievedEmails[$listName] = [$listRecipient];
        }

        $httpMockHandler->appendFixtures($httpFixtures);

        $returnValue = $this->retrieveAllRecipientsCommand->run(new ArrayInput([]), new NullOutput());
        $this->assertEquals(0, $returnValue);

        foreach ($listNames as $listName) {
            $listId = $listRecipientsService->getListId($listName);

            $listRecipientsRepository = $entityManager->getRepository(ListRecipients::class);

            /* @var ListRecipients $list */
            $list = $listRecipientsRepository->findOneBy([
                'listId' => $listId,
            ]);
            $listRecipients = ObjectReflector::getProperty($list, 'recipients');

            $this->assertEquals($expectedRetrievedEmails[$listName], $listRecipients);
        }
    }
}
