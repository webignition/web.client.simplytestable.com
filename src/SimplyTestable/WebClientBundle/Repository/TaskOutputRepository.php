<?php
namespace SimplyTestable\WebClientBundle\Repository;

use Doctrine\ORM\EntityRepository;

class TaskOutputRepository extends EntityRepository
{
    public function findHashlessOutput($limit = null) {
        $queryBuilder = $this->createQueryBuilder('TaskOutput');

        if(is_int($limit) && $limit > 0) {
            $queryBuilder->setMaxResults($limit);
        }

        $queryBuilder->select('TaskOutput');
        $queryBuilder->where('TaskOutput.hash IS NULL');

        return $queryBuilder->getQuery()->getResult();
    }

    public function findHashlessOutputIds($limit = null) {
        $queryBuilder = $this->createQueryBuilder('TaskOutput');

        if(is_int($limit) && $limit > 0) {
            $queryBuilder->setMaxResults($limit);
        }

        $queryBuilder->select('TaskOutput.id');
        $queryBuilder->where('TaskOutput.hash IS NULL');

        $result = $queryBuilder->getQuery()->getResult();

        if (count($result) === 0) {
            return array();
        }

        return $this->getSingleFieldCollectionFromResult($result, 'id');
    }


    public function findDuplicateHashes($limit = null) {
        $queryBuilder = $this->createQueryBuilder('TaskOutput');

        if(is_int($limit) && $limit > 0) {
            $queryBuilder->setMaxResults($limit);
        }

        $queryBuilder->select('TaskOutput.id');
        $queryBuilder->select('TaskOutput.hash');
        $queryBuilder->groupBy('TaskOutput.hash');
        $queryBuilder->having('COUNT(TaskOutput.id) > 1');

        $duplicateHashes = array();

        $result = $queryBuilder->getQuery()->getResult();

        foreach ($result as $duplicateHashResult) {
            $duplicateHashes[] = $duplicateHashResult['hash'];
        }

        return $duplicateHashes;
    }


    public function findIdsNotIn($excludeIds) {
        $queryBuilder = $this->createQueryBuilder('TaskOutput');
        $queryBuilder->select('TaskOutput.id');
        $queryBuilder->where('TaskOutput.id NOT IN ('.  implode(',', $excludeIds).')');

        $result = $queryBuilder->getQuery()->getResult();

        if (count($result) === 0) {
            return array();
        }

        $ids = array();

        foreach ($result as $taskOutputIdResult) {
            $ids[] = $taskOutputIdResult['id'];
        }

        sort($ids);

        return $ids;
    }


    private function getSingleFieldCollectionFromResult($result, $fieldName) {
        $collection = array();

        foreach ($result as $resultItem) {
            $collection[] = $resultItem[$fieldName];
        }

        return $collection;
    }


}