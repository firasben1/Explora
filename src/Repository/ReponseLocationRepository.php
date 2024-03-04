<?php

namespace App\Repository;

use App\Entity\ReponseLocation;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<ReponseLocation>
 *
 * @method ReponseLocation|null find($id, $lockMode = null, $lockVersion = null)
 * @method ReponseLocation|null findOneBy(array $criteria, array $orderBy = null)
 * @method ReponseLocation[]    findAll()
 * @method ReponseLocation[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ReponseLocationRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ReponseLocation::class);
    }

//    /**
//     * @return ReponseLocation[] Returns an array of ReponseLocation objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('r')
//            ->andWhere('r.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('r.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?ReponseLocation
//    {
//        return $this->createQueryBuilder('r')
//            ->andWhere('r.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
