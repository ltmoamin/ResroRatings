<?php

namespace App\Repository;

use App\Entity\Reponse;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Reponse>
 *
 * @method Reponse|null find($id, $lockMode = null, $lockVersion = null)
 * @method Reponse|null findOneBy(array $criteria, array $orderBy = null)
 * @method Reponse[]    findAll()
 * @method Reponse[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ReponseRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Reponse::class);
    }
    public function save(Reponse $reponse, bool $flush = false): void
    {
        $reclamation = $reponse->getReclamation();
        $reclamation->setEtatrec('resolue');
    
        $entityManager = $this->getEntityManager();
        $entityManager->persist($reclamation);
        $entityManager->persist($reponse);
    
        if ($flush) {
            $entityManager->flush();
        }
    }
    
    
        public function remove(Reponse $entity, bool $flush = false): void
        {
            $this->getEntityManager()->remove($entity);
    
            if ($flush) {
                $this->getEntityManager()->flush();
            }
        }
//    /**
//     * @return Reponse[] Returns an array of Reponse objects
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

//    public function findOneBySomeField($value): ?Reponse
//    {
//        return $this->createQueryBuilder('r')
//            ->andWhere('r.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
public function findOneByReclamation(Reclamation $reclamation): ?Reponse
{
    return $this->createQueryBuilder('r')
        ->andWhere('r.reclamation = :reclamation')
        ->setParameter('reclamation', $reclamation)
        ->getQuery()
        ->getOneOrNullResult();
}

public function countReponses(): int
{
    return $this->createQueryBuilder('rr')
        ->select('COUNT(rr.idrep)')
        ->getQuery()
        ->getSingleScalarResult();
}
}
