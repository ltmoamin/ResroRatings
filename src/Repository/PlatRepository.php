<?php

namespace App\Repository;

use App\Entity\Plat;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Plat>
 *
 * @method Plat|null find($id, $lockMode = null, $lockVersion = null)
 * @method Plat|null findOneBy(array $criteria, array $orderBy = null)
 * @method Plat[]    findAll()
 * @method Plat[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PlatRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Plat::class);
    }

//    /**
//     * @return Plat[] Returns an array of Plat objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('p')
//            ->andWhere('p.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('p.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Plat
//    {
//        return $this->createQueryBuilder('p')
//            ->andWhere('p.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }


public function findByCategory($category)
{
    return $this->createQueryBuilder('p')
        ->andWhere('p.categorie = :category')
        ->setParameter('category', $category)
        ->getQuery()
        ->getResult();
}

public function findEntitiesByString($str){
    return $this->getEntityManager()
        ->createQuery(
            'SELECT p
            FROM App\Entity\Plat p
            WHERE p.nom LIKE :str 
            OR p.categorie LIKE :str 
            OR p.prix LIKE :str'
        )
        ->setParameter('str', '%'.$str.'%')
        ->getResult();
}

public function findByPriceRange($minPrice, $maxPrice)
{
    return $this->createQueryBuilder('p')
        ->andWhere('p.prix >= :minPrice')
        ->andWhere('p.prix <= :maxPrice')
        ->setParameter('minPrice', $minPrice)
        ->setParameter('maxPrice', $maxPrice)
        ->getQuery()
        ->getResult();
}

public function findByNom($nom)
{
    return $this->createQueryBuilder('p')
        ->andWhere('p.nom LIKE :nom')
        ->setParameter('nom', '%' . $nom . '%')
        ->getQuery()
        ->getResult();
}

}
