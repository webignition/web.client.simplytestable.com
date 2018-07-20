<?php

namespace Tests\AppBundle\Functional\Repository;

use Doctrine\ORM\EntityManagerInterface;
use AppBundle\Entity\CacheValidatorHeaders;
use AppBundle\Entity\Task\Task;
use AppBundle\Entity\Test\Test;
use AppBundle\Repository\CacheValidatorHeadersRepository;
use AppBundle\Repository\TaskRepository;
use AppBundle\Services\CacheValidatorHeadersService;
use Tests\AppBundle\Factory\OutputFactory;
use Tests\AppBundle\Factory\TaskFactory;
use Tests\AppBundle\Factory\TestFactory;
use Tests\AppBundle\Functional\AbstractBaseTestCase;

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

        $this->cacheValidatorHeadersService = self::$container->get(CacheValidatorHeadersService::class);

        /* @var EntityManagerInterface $entityManager */
        $entityManager = self::$container->get(EntityManagerInterface::class);
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
}