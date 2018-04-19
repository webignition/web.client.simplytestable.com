<?php

namespace SimplyTestable\WebClientBundle\Repository;

use Doctrine\ORM\EntityRepository;

class CacheValidatorHeadersRepository extends EntityRepository
{
    /**
     * @param int|null $limit
     *
     * @return int[]
     */
    public function findAll($limit = null)
    {
        $queryBuilder = $this->createQueryBuilder('CacheValidatorHeaders');
        $queryBuilder->select('CacheValidatorHeaders');
        $queryBuilder->orderBy('CacheValidatorHeaders.id', 'DESC');

        if (!is_null($limit)) {
            $queryBuilder->setMaxResults($limit);
        }

        return $queryBuilder->getQuery()->getResult();
    }

    /**
     * @return int
     */
    public function count()
    {
        $queryBuilder = $this->createQueryBuilder('CacheValidatorHeaders');
        $queryBuilder->select('COUNT(CacheValidatorHeaders.id)');

        $result = $queryBuilder->getQuery()->getResult();

        return $result[0][1];
    }
}
