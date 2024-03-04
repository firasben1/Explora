<?php

namespace App\Repository;

use App\Entity\Reclamation;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;


/**
 * @extends ServiceEntityRepository<Reclamation>
 *
 * @method Reclamation|null find($id, $lockMode = null, $lockVersion = null)
 * @method Reclamation|null findOneBy(array $criteria, array $orderBy = null)
 * @method Reclamation[]    findAll()
 * @method Reclamation[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ReclamationRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Reclamation::class);
    }

    /**
     * Trouve toutes les réclamations triées par un champ spécifique.
     *
     * @param string $sortBy Le champ par lequel trier les réclamations
     * @return Reclamation[] Les réclamations triées
     */
    
     public function remove(Reclamation $entity, bool $flush = false): void
     {
         $this->getEntityManager()->remove($entity);
 
         if ($flush) {
             $this->getEntityManager()->flush();
         }
     }


    public function findAllSortedBy(string $sortBy, string $sortOrder): array
    {
        $qb = $this->createQueryBuilder('r')
            ->orderBy('r.' . $sortBy, $sortOrder);
    
        return $qb->getQuery()->getResult();
    }

    //eye
    public function findAllWithResponsesSortedBy($sortBy, $sortOrder)
{
    $queryBuilder = $this->createQueryBuilder('r')
        ->leftJoin('r.reponse', 'reponse')
        ->addSelect('reponse');

    // Add sorting
    if ($sortBy === 'id') {
        $queryBuilder->orderBy('r.id', $sortOrder);
    } elseif ($sortBy === 'date') {
        $queryBuilder->orderBy('r.date', $sortOrder);
    }

    return $queryBuilder->getQuery()->getResult();
}

  //public function findReclamationWithResponse($id)
    //{
      //  return $this->createQueryBuilder('r')
        //    ->leftJoin('r.reponse', 'reponse')
          //  ->addSelect('reponse')
            //->andWhere('r.id = :id')
            //->setParameter('id', $id)
            //->getQuery()
            //->getOneOrNullResult();
   // }

    


}