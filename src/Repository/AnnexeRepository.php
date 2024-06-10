<?php

namespace App\Repository;

use App\Entity\Annexe;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;


/**
 * @extends ServiceEntityRepository<Annexe>
 *
 * @method Annexe|null find($id, $lockMode = null, $lockVersion = null)
 * @method Annexe|null findOneBy(array $criteria, array $orderBy = null)
 * @method Annexe[]    findAll()
 * @method Annexe[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class AnnexeRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Annexe::class);
    }

//    /**
//     * @return Annexe[] Returns an array of Annexe objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('a')
//            ->andWhere('a.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('a.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Annexe
//    {
//        return $this->createQueryBuilder('a')
//            ->andWhere('a.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
