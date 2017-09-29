<?php

namespace AppBundle\Repository;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\QueryBuilder;

/**
 * ContactRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class ContactRepository extends \Doctrine\ORM\EntityRepository
{
    public function findSingleContact()
    {
        $qb = $this->createQueryBuilder('c')->setMaxResults(1);
        
        return $qb
            ->getQuery()
            ->getSingleResult()
        ;    
    }
}
