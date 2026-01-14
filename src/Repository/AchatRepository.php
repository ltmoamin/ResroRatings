<?php

namespace App\Repository;

use App\Entity\Achat;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Achat>
 *
 * @method Achat|null find($id, $lockMode = null, $lockVersion = null)
 * @method Achat|null findOneBy(array $criteria, array $orderBy = null)
 * @method Achat[]    findAll()
 * @method Achat[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class AchatRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Achat::class);
    }

//    /**
//     * @return Achat[] Returns an array of Achat objects
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

//    public function findOneBySomeField($value): ?Achat
//    {
//        return $this->createQueryBuilder('a')
//            ->andWhere('a.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
public function findByType(string $type): array
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.type = :type')
            ->setParameter('type', $type)
            ->getQuery()
            ->getResult();
    }



public function findByDate(\DateTime $date)
{
    return $this->createQueryBuilder('a')
        ->andWhere('a.date = :date')
        ->setParameter('date', $date)
        ->getQuery()
        ->getResult();
}




public function findTop3Plats()
{
    $entityManager = $this->getEntityManager();

    $query = $entityManager->createQuery(
        'SELECT p.nom as platNom,p.prix as prix , p.image as imagePath, SUM(a.quantite) as totalQuantite
        FROM App\Entity\Achat a
        JOIN a.plat p
        GROUP BY p.nom, p.image
        ORDER BY totalQuantite DESC'
    )->setMaxResults(3);

    return $query->getResult();
}
public function findPlatsCountByCategoryAndDate(\DateTimeInterface $date): array
    {
        $entityManager = $this->getEntityManager();

        $query = $entityManager->createQuery(
            'SELECT p.categorie AS category, COUNT(p) AS totalPlats
             FROM App\Entity\Achat a
             JOIN a.plat p
             WHERE a.date = :date
             GROUP BY p.categorie'
        )->setParameter('date', $date);

        return $query->getResult();
    }
}
