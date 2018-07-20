<?php
namespace AppBundle\Services;

use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use AppBundle\Entity\CacheValidatorHeaders;

class CacheValidatorHeadersService
{
    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

    /**
     * @var EntityRepository
     */
    private $entityRepository;

    /**
     * @param EntityManagerInterface $entityManager
     */
    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
        $this->entityRepository = $this->entityManager->getRepository(CacheValidatorHeaders::class);
    }

    /**
     * @param string $identifier
     *
     * @return CacheValidatorHeaders
     */
    public function get($identifier)
    {
        $cacheValidatorHeader = $this->fetch($identifier);
        if (is_null($cacheValidatorHeader)) {
            $cacheValidatorHeader = $this->create($identifier);
        }

        return $cacheValidatorHeader;
    }

    /**
     * @param int|null $limit
     */
    public function clear($limit = null)
    {
        $all = $this->entityRepository->findAll($limit);

        foreach ($all as $cacheValidatorHeaders) {
            $this->entityManager->remove($cacheValidatorHeaders);
        }

        $this->entityManager->flush();
    }

    /**
     * @return int
     */
    public function count()
    {
        return $this->entityRepository->count([]);
    }

    /**
     * @param string $identifier
     *
     * @return CacheValidatorHeaders
     */
    private function fetch($identifier)
    {
        /* @var CacheValidatorHeaders $cacheValidatorHeaders */
        $cacheValidatorHeaders = $this->entityRepository->findOneBy([
            'identifier' => $identifier
        ]);

        return $cacheValidatorHeaders;
    }

    /**
     * @param string $identifier
     *
     * @return CacheValidatorHeaders
     */
    private function create($identifier)
    {
        $cacheValidatorHeaders = new CacheValidatorHeaders();
        $cacheValidatorHeaders->setIdentifier($identifier);
        $cacheValidatorHeaders->setLastModifiedDate(new \DateTime());

        $this->entityManager->persist($cacheValidatorHeaders);
        $this->entityManager->flush();

        return $cacheValidatorHeaders;
    }
}
