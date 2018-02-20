<?php

namespace Tests\WebClientBundle\Functional\Services;

use Doctrine\ORM\EntityManagerInterface;
use SimplyTestable\WebClientBundle\Entity\CacheValidatorHeaders;
use SimplyTestable\WebClientBundle\Services\CacheValidatorHeadersService;
use Tests\WebClientBundle\Functional\AbstractBaseTestCase;

class CacheValidatorHeadersServiceTest extends AbstractBaseTestCase
{
    /**
     * @var CacheValidatorHeadersService
     */
    private $cacheValidatorHeadersService;

    /**
     * {@inheritdoc}
     */
    protected function setUp()
    {
        parent::setUp();

        $this->cacheValidatorHeadersService = $this->container->get(CacheValidatorHeadersService::class);
    }

    public function testGet()
    {
        /* @var EntityManagerInterface $entityManager */
        $entityManager = $this->container->get('doctrine.orm.entity_manager');
        $cacheValidatorHeadersRepository = $entityManager->getRepository(CacheValidatorHeaders::class);

        $this->assertEmpty($cacheValidatorHeadersRepository->findAll());

        $cacheValidatorHeaders = $this->cacheValidatorHeadersService->get('foo');

        $this->assertInstanceOf(CacheValidatorHeaders::class, $cacheValidatorHeaders);

        $this->assertEquals(
            $cacheValidatorHeaders,
            $cacheValidatorHeadersRepository->findOneBy([
                'identifier' => $cacheValidatorHeaders->getIdentifier(),
            ])
        );
    }

    public function testClear()
    {
        /* @var EntityManagerInterface $entityManager */
        $entityManager = $this->container->get('doctrine.orm.entity_manager');
        $cacheValidatorHeadersRepository = $entityManager->getRepository(CacheValidatorHeaders::class);

        $this->assertEmpty($cacheValidatorHeadersRepository->findAll());

        $this->cacheValidatorHeadersService->get('foo');
        $this->assertNotEmpty($cacheValidatorHeadersRepository->findAll());

        $this->cacheValidatorHeadersService->clear();
        $this->assertEmpty($cacheValidatorHeadersRepository->findAll());
    }
}
