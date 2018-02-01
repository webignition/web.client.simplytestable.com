<?php

namespace SimplyTestable\WebClientBundle\Tests\Functional\Command\CacheValidator;

use SimplyTestable\WebClientBundle\Command\CacheValidator\ClearCommand;
use SimplyTestable\WebClientBundle\Entity\CacheValidatorHeaders;
use SimplyTestable\WebClientBundle\Tests\Functional\AbstractBaseTestCase;
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

        $this->clearCommand = $this->container->get(ClearCommand::class);
    }

    public function testRun()
    {
        $cacheValidatorHeadersService = $this->container->get('simplytestable.services.cachevalidatorheadersservice');
        $entityManager = $this->container->get('doctrine.orm.entity_manager');
        $cacheValidatorHeadersRepository = $entityManager->getRepository(CacheValidatorHeaders::class);

        $cacheValidatorHeadersService->get('foo');

        $this->assertNotEmpty($cacheValidatorHeadersRepository->findAll());

        $this->clearCommand->run(new ArrayInput([]), new NullOutput());

        $this->assertEmpty($cacheValidatorHeadersRepository->findAll());
    }
}
