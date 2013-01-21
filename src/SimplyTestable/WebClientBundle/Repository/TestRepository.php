<?php
namespace SimplyTestable\WebClientBundle\Repository;

use Doctrine\ORM\EntityRepository;
use SimplyTestable\WebClientBundle\Entity\Test\Test;

class TestRepository extends EntityRepository
{  
    public function hasById($test_id) {        
        $queryBuilder = $this->createQueryBuilder('Test');
        $queryBuilder->select('count(Test.id)');
        $queryBuilder->where('Test.id = :TestId');
        $queryBuilder->setParameter('TestId', $test_id); 
        
        $result = $queryBuilder->getQuery()->getResult();        
        
        return $result[0][1] > 0;     
    }    
    
    public function getById($test_id) {        
        $queryBuilder = $this->createQueryBuilder('Test');
        $queryBuilder->select('Test');
        $queryBuilder->where('Test.id = :TestId');
        $queryBuilder->setParameter('TestId', $test_id); 
        
        $result = $queryBuilder->getQuery()->getResult();        
        
        return (count($result) === 0) ? null : $result[0];      
    }
    
    
    public function hasForWebsite($website) {            
        $queryBuilder = $this->createQueryBuilder('Test');
        $queryBuilder->select('count(Test.testId)');
        $queryBuilder->where('Test.website = :Website');
        $queryBuilder->setParameter('Website', $website);        
        $queryBuilder->orderBy('Test.id', 'DESC');
        
        $result = $queryBuilder->getQuery()->getResult();        

        return $result[0][1] > 0;
    }
    
    
    public function getLatestId($website) {        
        $queryBuilder = $this->createQueryBuilder('Test');
        $queryBuilder->select('Test.testId');
        $queryBuilder->where('Test.website = :Website');
        $queryBuilder->setParameter('Website', $website);
        $queryBuilder->orderBy('Test.id', 'DESC');
        $queryBuilder->setMaxResults(1);
        
        $result = $queryBuilder->getQuery()->getResult();
        
        if (count($result) == 0) {
            return null;
        }        
        
        return (int)$result[0]['testId'];      
    }
}