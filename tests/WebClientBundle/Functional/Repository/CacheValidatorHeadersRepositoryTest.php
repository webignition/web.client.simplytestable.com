<?php

namespace Tests\WebClientBundle\Functional\Repository;

use Doctrine\ORM\EntityManagerInterface;
use SimplyTestable\WebClientBundle\Entity\CacheValidatorHeaders;
use SimplyTestable\WebClientBundle\Entity\Task\Task;
use SimplyTestable\WebClientBundle\Entity\Test\Test;
use SimplyTestable\WebClientBundle\Repository\CacheValidatorHeadersRepository;
use SimplyTestable\WebClientBundle\Repository\TaskRepository;
use SimplyTestable\WebClientBundle\Services\CacheValidatorHeadersService;
use Tests\WebClientBundle\Factory\OutputFactory;
use Tests\WebClientBundle\Factory\TaskFactory;
use Tests\WebClientBundle\Factory\TestFactory;
use Tests\WebClientBundle\Functional\AbstractBaseTestCase;

class CacheValidatorHeadersRepositoryTest extends AbstractBaseTestCase
{
    /**
     * @var CacheValidatorHeadersService
     */
    private $cacheValidatorHeadersService;

    /**
     * @var CacheValidatorHeadersRepository
     */
    private $cacheValidatorHeadersRepository;

    /**
     * {@inheritdoc}
     */
    protected function setUp()
    {
        parent::setUp();

        $this->cacheValidatorHeadersService = $this->container->get(CacheValidatorHeadersService::class);

        /* @var EntityManagerInterface $entityManager */
        $entityManager = $this->container->get('doctrine.orm.entity_manager');
        $this->cacheValidatorHeadersRepository = $entityManager->getRepository(CacheValidatorHeaders::class);
    }

    /**
     * @dataProvider findAllDataProvider
     *
     * @param int|null $limit
     * @param int[] $expectedCacheValidatorHeaderIndices
     */
    public function testFindAll($limit, array $expectedCacheValidatorHeaderIndices)
    {
        /* @var CacheValidatorHeaders[] $cacheValidatorHeadersCollection */
        $cacheValidatorHeadersCollection = [];

        for ($identifier = 0; $identifier < 10; $identifier++) {
            $cacheValidatorHeadersCollection[] = $this->cacheValidatorHeadersService->get($identifier);
        }

        $expectedCacheValidatorHeaderIds = [];
        foreach ($cacheValidatorHeadersCollection as $index => $cacheValidatorHeaders) {
            if (in_array($index, $expectedCacheValidatorHeaderIndices)) {
                $expectedCacheValidatorHeaderIds[] = $cacheValidatorHeaders->getId();
            }
        }

        /* @var CacheValidatorHeaders[] $retrievedCacheValidatorHeadersCollection */
        $retrievedCacheValidatorHeadersCollection = $this->cacheValidatorHeadersRepository->findAll($limit);

        $resultIds = [];
        foreach ($retrievedCacheValidatorHeadersCollection as $retrievedCacheValidatorHeaders) {
            $resultIds[] = $retrievedCacheValidatorHeaders->getId();
        }

        $this->assertEquals(array_reverse($expectedCacheValidatorHeaderIds), $resultIds);
    }

    /**
     * @return array
     */
    public function findAllDataProvider()
    {
        return [
            'no limit' => [
                'limit' => null,
                'expectedCacheValidatorHeaderIndices' => [0, 1, 2, 3, 4, 5, 6, 7, 8, 9],
            ],
            'limit: 0' => [
                'limit' => 0,
                'expectedCacheValidatorHeaderIndices' => [],
            ],
            'limit: 1' => [
                'limit' => 1,
                'expectedCacheValidatorHeaderIndices' => [9],
            ],
            'limit: 2' => [
                'limit' => 2,
                'expectedCacheValidatorHeaderIndices' => [8, 9],
            ],
            'limit: 3' => [
                'limit' => 3,
                'expectedCacheValidatorHeaderIndices' => [7, 8, 9],
            ],
            'limit: 9' => [
                'limit' => 9,
                'expectedCacheValidatorHeaderIndices' => [1, 2, 3, 4, 5, 6, 7, 8, 9],
            ],
            'limit: 10' => [
                'limit' => 10,
                'expectedCacheValidatorHeaderIndices' => [0, 1, 2, 3, 4, 5, 6, 7, 8, 9],
            ],
            'limit: 11' => [
                'limit' => 11,
                'expectedCacheValidatorHeaderIndices' => [0, 1, 2, 3, 4, 5, 6, 7, 8, 9],
            ],
        ];
    }

    public function testCount()
    {
        $this->assertEquals(0, $this->cacheValidatorHeadersRepository->count());

        $this->cacheValidatorHeadersService->get(0);
        $this->assertEquals(1, $this->cacheValidatorHeadersRepository->count());

        $this->cacheValidatorHeadersService->get(1);
        $this->assertEquals(2, $this->cacheValidatorHeadersRepository->count());

        $this->cacheValidatorHeadersService->get(2);
        $this->assertEquals(3, $this->cacheValidatorHeadersRepository->count());
    }
}