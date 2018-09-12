<?php

namespace App\Tests\Functional\Command\EmailList;

use Doctrine\ORM\EntityManagerInterface;
use App\Command\EmailList\RetrieveListRecipientsCommand;
use App\Entity\MailChimp\ListRecipients;
use App\Services\MailChimp\ListRecipientsService;
use App\Tests\Factory\HttpResponseFactory;
use App\Tests\Functional\AbstractBaseTestCase;
use Mockery\MockInterface;
use Symfony\Component\Console\Helper\HelperSet;
use Symfony\Component\Console\Helper\QuestionHelper;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\NullOutput;
use App\Tests\Services\HttpMockHandler;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\ChoiceQuestion;

class RetrieveListRecipientsCommandTest extends AbstractBaseTestCase
{
    /**
     * @var RetrieveListRecipientsCommand
     */
    private $retrieveListRecipientsCommand;

    /**
     * {@inheritdoc}
     */
    protected function setUp()
    {
        parent::setUp();

        $this->retrieveListRecipientsCommand = self::$container->get(RetrieveListRecipientsCommand::class);
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
        $entityManager = self::$container->get(EntityManagerInterface::class);
        $listRecipientsService = self::$container->get(ListRecipientsService::class);
        $httpMockHandler = self::$container->get(HttpMockHandler::class);

        $httpMockHandler->appendFixtures($httpFixtures);

        $input = new ArrayInput([
            RetrieveListRecipientsCommand::ARG_LIST_NAME => $listName,
        ]);

        $returnValue = $this->retrieveListRecipientsCommand->run($input, new NullOutput());

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

    public function testRunWithEmptyListName()
    {
        $listRecipientsService = self::$container->get(ListRecipientsService::class);

        $input = new ArrayInput([]);
        $output = new NullOutput();

        /* @var QuestionHelper|MockInterface $questionHelper */
        $questionHelper = \Mockery::mock(QuestionHelper::class);
        $questionHelper
            ->shouldReceive('ask')
            ->once()
            ->withArgs(function (
                InputInterface $inputArg,
                OutputInterface $outputArg,
                ChoiceQuestion $question
            ) use (
                $input,
                $output,
                $listRecipientsService
            ) {
                $this->assertEquals($input, $inputArg);
                $this->assertEquals($output, $outputArg);
                $this->assertEquals($listRecipientsService->getListNames(), $question->getChoices());

                return true;
            });


        /* @var HelperSet|MockInterface $helperSet */
        $helperSet = \Mockery::mock(HelperSet::class);
        $helperSet
            ->shouldReceive('get')
            ->once()
            ->with('question')
            ->andReturn($questionHelper);

        $this->retrieveListRecipientsCommand->setHelperSet($helperSet);

        $returnValue = $this->retrieveListRecipientsCommand->run($input, $output);

        $this->assertEquals(0, $returnValue);
    }
}
