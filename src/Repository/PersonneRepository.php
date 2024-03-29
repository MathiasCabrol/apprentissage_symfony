<?php

namespace App\Repository;

use App\Entity\Personne;
use Doctrine\ORM\QueryBuilder;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;

/**
 * @extends ServiceEntityRepository<Personne>
 *
 * @method Personne|null find($id, $lockMode = null, $lockVersion = null)
 * @method Personne|null findOneBy(array $criteria, array $orderBy = null)
 * @method Personne[]    findAll()
 * @method Personne[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PersonneRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Personne::class);
    }

    public function add(Personne $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Personne $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }


   public function findPersonneByAgeInterval($min, $max): array
   {
       $qb = $this->createQueryBuilder('p');
       $this->addIntervalAge($qb, $min, $max);
       return $qb->getQuery()->getResult();   
   }

   public function statsPersonnesByAgeInterval($min, $max): array
   {
       $qb = $this->createQueryBuilder('p')
           ->select('avg(p.age) as ageMoyen, count(p.id) as nombrePersonne');
           $this->addIntervalAge($qb, $min, $max);
        return $qb->getQuery()->getScalarResult();
           
       ;
   }

   private function addIntervalAge(QueryBuilder $qb, $ageMin, $ageMax) : void
   {
       $qb->andWhere('p.age >= :min and p.age <= :max')
       ->orderBy('p.age', 'ASC')
       ->setParameters(['min' => $ageMin, 'max' => $ageMax]);
   }

//    public function findOneBySomeField($value): ?Personne
//    {
//        return $this->createQueryBuilder('p')
//            ->andWhere('p.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
