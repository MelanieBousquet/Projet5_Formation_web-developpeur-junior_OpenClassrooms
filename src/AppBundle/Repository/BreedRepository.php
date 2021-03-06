<?php

namespace AppBundle\Repository;

/**
 * BreedRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class BreedRepository extends \Doctrine\ORM\EntityRepository
{
    public function getCatBreeds()
    {
        $qb = $this->createQueryBuilder('b');

        $qb
            ->innerJoin('b.type','t')
            ->addSelect('t')
            ->andWhere('t.name = :name')
            ->setParameter('name', 'chat')
        ;

        return $qb
            ->getQuery()
            ->getResult()
        ;
    }    
    
    public function getDogBreeds()
    {
        $qb = $this->createQueryBuilder('b');

        $qb
            ->innerJoin('b.type','t')
            ->addSelect('t')
            ->andWhere('t.name = :name')
            ->setParameter('name', 'chien')
        ;

        return $qb
            ->getQuery()
            ->getResult()
        ;
    }
}
