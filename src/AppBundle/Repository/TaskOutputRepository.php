<?php

namespace AppBundle\Repository;

use Doctrine\ORM\EntityRepository;

class TaskOutputRepository extends EntityRepository
{
    /**
     * @param int|null $limit
     *
     * @return int[]
     */
    public function findHashlessOutputIds($limit = null)
    {
        $queryBuilder = $this->createQueryBuilder('TaskOutput');
        $queryBuilder->select('TaskOutput.id');
        $queryBuilder->where('TaskOutput.hash IS NULL');

        if (!empty($limit)) {
            $queryBuilder->setMaxResults($limit);
        }

        $result = $queryBuilder->getQuery()->getResult();

        return $this->getSingleFieldCollectionFromResult($result, 'id');
    }

    /**
     * @param int|null $limit
     *
     * @return string[]
     */
    public function findDuplicateHashes($limit = null)
    {
        $queryBuilder = $this->createQueryBuilder('TaskOutput');

        $queryBuilder->select('TaskOutput.id');
        $queryBuilder->select('TaskOutput.hash');
        $queryBuilder->groupBy('TaskOutput.hash');
        $queryBuilder->having('COUNT(TaskOutput.id) > 1');

        if (!empty($limit)) {
            $queryBuilder->setMaxResults($limit);
        }

        $duplicateHashes = [];

        $result = $queryBuilder->getQuery()->getResult();

        foreach ($result as $duplicateHashResult) {
            $duplicateHashes[] = $duplicateHashResult['hash'];
        }

        return $duplicateHashes;
    }

    /**
     * @param int[] $excludeIds
     *
     * @return int[]
     */
    public function findIdsNotIn($excludeIds)
    {
        $queryBuilder = $this->createQueryBuilder('TaskOutput');
        $queryBuilder->select('TaskOutput.id');

        if (!empty($excludeIds)) {
            $queryBuilder->where('TaskOutput.id NOT IN (:ExcludeIds)');
            $queryBuilder->setParameter('ExcludeIds', $excludeIds);
        }

        $result = $queryBuilder->getQuery()->getResult();

        $ids = [];

        foreach ($result as $taskOutputIdResult) {
            $ids[] = $taskOutputIdResult['id'];
        }

        sort($ids);

        return $ids;
    }

    /**
     * @param array $result
     * @param string $fieldName
     *
     * @return array
     */
    private function getSingleFieldCollectionFromResult($result, $fieldName)
    {
        $collection = [];

        foreach ($result as $resultItem) {
            $collection[] = $resultItem[$fieldName];
        }

        return $collection;
    }
}
