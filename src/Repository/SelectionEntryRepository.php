<?php

namespace App\Repository;

use App\Entity\SelectionEntry;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method SelectionEntry|null find($id, $lockMode = null, $lockVersion = null)
 * @method SelectionEntry|null findOneBy(array $criteria, array $orderBy = null)
 * @method SelectionEntry[]    findAll()
 * @method SelectionEntry[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class SelectionEntryRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, SelectionEntry::class);
    }

    /**
     * @param $value string
     * @return SelectionEntry[] Returns an array of SelectionEntry objects
     */
    public function findByType($value)
    {
        return $this->createQueryBuilder('s')
            ->where("s.type LIKE :value")
            ->setParameter('value', "%$value%")
            ->getQuery()
            ->getResult();
    }

    /**
     * @param $values array
     * @return SelectionEntry[] Returns an array of SelectionEntry objects
     */
    public function findByTypes($values)
    {
        return $this->createQueryBuilder('s')
            ->where("s.type IN(:values)")
            ->setParameter('values', $values)
            ->getQuery()
            ->getResult();
    }


    /*
    public function findOneBySomeField($value): ?SelectionEntry
    {
        return $this->createQueryBuilder('s')
            ->andWhere('s.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
