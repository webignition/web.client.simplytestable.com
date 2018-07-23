<?php

namespace Tests\AppBundle\Functional\Services;

use Doctrine\ORM\EntityManagerInterface;
use App\Entity\CacheValidatorHeaders;
use App\Repository\CacheValidatorHeadersRepository;
use App\Services\CacheValidatorHeadersService;
use Tests\AppBundle\Functional\AbstractBaseTestCase;

class CacheValidatorHeadersServiceTest extends AbstractBaseTestCase
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

    public function testGet()
    {
        $this->assertEmpty($this->cacheValidatorHeadersRepository->findAll());

        $cacheValidatorHeaders = $this->cacheValidatorHeadersService->get('foo');

        $this->assertInstanceOf(CacheValidatorHeaders::class, $cacheValidatorHeaders);

        $this->assertEquals(
            $cacheValidatorHeaders,
            $this->cacheValidatorHeadersRepository->findOneBy([
                'identifier' => $cacheValidatorHeaders->getIdentifier(),
            ])
        );
    }

    /**
     * @dataProvider clearDataProvider
     *
     * @param $limit
     * @param array $expectedCacheValidatorHeaderIndices
     */
    public function testClear($limit, array $expectedCacheValidatorHeaderIndices)
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

        $this->assertEquals(
            array_reverse($cacheValidatorHeadersCollection),
            $this->cacheValidatorHeadersRepository->findAll()
        );

        $this->cacheValidatorHeadersService->clear($limit);

        /* @var CacheValidatorHeaders[] $allCacheValidatorHeaders */
        $allCacheValidatorHeaders = $this->cacheValidatorHeadersRepository->findAll();
        $cacheValidatorHeaderIds = [];

        foreach ($allCacheValidatorHeaders as $cacheValidatorHeader) {
            $cacheValidatorHeaderIds[] = $cacheValidatorHeader->getId();
        }

        $this->assertEquals(array_reverse($expectedCacheValidatorHeaderIds), $cacheValidatorHeaderIds);
    }

    /**
     * @return array
     */
    public function clearDataProvider()
    {
        return [
            'no limit' => [
                'limit' => null,
                'expectedCacheValidatorHeaderIndices' => [],
            ],
            'limit: 0' => [
                'limit' => 0,
                'expectedCacheValidatorHeaderIndices' => [0, 1, 2, 3, 4, 5, 6, 7, 8, 9],
            ],
            'limit: 1' => [
                'limit' => 1,
                'expectedCacheValidatorHeaderIndices' => [0, 1, 2, 3, 4, 5, 6, 7, 8],
            ],
            'limit: 2' => [
                'limit' => 2,
                'expectedCacheValidatorHeaderIndices' => [0, 1, 2, 3, 4, 5, 6, 7],
            ],
            'limit: 3' => [
                'limit' => 3,
                'expectedCacheValidatorHeaderIndices' => [0, 1, 2, 3, 4, 5, 6],
            ],
            'limit: 9' => [
                'limit' => 9,
                'expectedCacheValidatorHeaderIndices' => [0],
            ],
            'limit: 10' => [
                'limit' => 10,
                'expectedCacheValidatorHeaderIndices' => [],
            ],
            'limit: 11' => [
                'limit' => 11,
                'expectedCacheValidatorHeaderIndices' => [],
            ],
        ];
    }

    public function testCount()
    {
        $this->assertEquals(0, $this->cacheValidatorHeadersService->count());

        $this->cacheValidatorHeadersService->get(0);
        $this->assertEquals(1, $this->cacheValidatorHeadersService->count());

        $this->cacheValidatorHeadersService->get(1);
        $this->assertEquals(2, $this->cacheValidatorHeadersService->count());

        $this->cacheValidatorHeadersService->get(2);
        $this->assertEquals(3, $this->cacheValidatorHeadersService->count());
    }
}
