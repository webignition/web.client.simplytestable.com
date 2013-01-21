<?php
namespace SimplyTestable\WebClientBundle\Repository;

use Doctrine\ORM\EntityRepository;

class TaskOutputRepository extends EntityRepository
{
    
    public function findOutputByhash($hash) {
        $queryBuilder = $this->createQueryBuilder('TaskOutput');
        $queryBuilder->setMaxResults(1);
        $queryBuilder->select('TaskOutput');
        $queryBuilder->where('TaskOutput.hash = :Hash');
        $queryBuilder->setParameter('Hash', $hash);
        
        $result = $queryBuilder->getQuery()->getResult();
        if (count($result) === 0) {
            return null;
        }
        
        return $result[0];
    }  
    
    
    public function findHashlessOutput($limit = null) {
        $queryBuilder = $this->createQueryBuilder('TaskOutput');
        
        if(is_int($limit) && $limit < 0) {
            $queryBuilder->setMaxResults($limit);
        }        

        $queryBuilder->select('TaskOutput');
        $queryBuilder->where('TaskOutput.hash IS NULL');
        
        return $queryBuilder->getQuery()->getResult();      
    }
}