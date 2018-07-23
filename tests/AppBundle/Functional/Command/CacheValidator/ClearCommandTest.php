<?php

namespace Tests\AppBundle\Functional\Command\CacheValidator;

use Doctrine\ORM\EntityManagerInterface;
use App\Command\CacheValidator\ClearCommand;
use App\Entity\CacheValidatorHeaders;
use App\Services\CacheValidatorHeadersService;
use Tests\AppBundle\Functional\AbstractBaseTestCase;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Output\NullOutput;

class ClearCommandTest extends AbstractBaseTestCase
{
    /**
     * @var ClearCommand
     */
    protected $clearCommand;

    /**
     * {@inheritdoc}
     */
    protected function setUp()
    {
        parent::setUp();

        $this->clearCommand = self::$container->get(ClearCommand::class);
    }

    public function testRun()
    {
        $cacheValidatorHeadersService = self::$container->get(CacheValidatorHeadersService::class);
        $entityManager = self::$container->get(EntityManagerInterface::class);
        $cacheValidatorHeadersRepository = $entityManager->getRepository(CacheValidatorHeaders::class);

        $cacheValidatorHeadersService->get('foo');

        $this->assertNotEmpty($cacheValidatorHeadersRepository->findAll());

        $this->clearCommand->run(new ArrayInput([]), new NullOutput());

        $this->assertEmpty($cacheValidatorHeadersRepository->findAll());
    }
}
