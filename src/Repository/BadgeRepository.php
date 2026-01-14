<?php

namespace App\Repository;

use App\Entity\Badge;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Badge>
 *
 * @method Badge|null find($id, $lockMode = null, $lockVersion = null)
 * @method Badge|null findOneBy(array $criteria, array $orderBy = null)
 * @method Badge[]    findAll()
 * @method Badge[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class BadgeRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Badge::class);
    }

//    /**
//     * @return Badge[] Returns an array of Badge objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('b')
//            ->andWhere('b.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('b.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Badge
//    {
//        return $this->createQueryBuilder('b')
//            ->andWhere('b.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
public function getBadgeCountsByType(): array
    {
        return $this->createQueryBuilder('b')
            ->select('b.typebadge AS typebadge, COUNT(b.id) AS badgeCount')
            ->groupBy('b.typebadge')
            ->getQuery()
            ->getResult();
    }

  
    public function findByType(string $badgeType): array
    {
        return $this->createQueryBuilder('b')
            ->andWhere('b.typebadge = :type')
            ->setParameter('type', $badgeType)
            ->getQuery()
            ->getResult();
    }

    public function findBadgeByRestaurantAndType(string $restaurantName, string $badgeType): ?Badge
    {
        return $this->createQueryBuilder('b')
            ->andWhere('b.restaurant = :restaurant')
            ->andWhere('b.typebadge = :badgeType')
            ->setParameter('restaurant', $restaurantName)
            ->setParameter('badgeType', $badgeType)
            ->getQuery()
            ->getOneOrNullResult();
    }
  // BadgeRepository.php

// public function countBadgesByType(): array
// {
//     return $this->createQueryBuilder('b')
//         ->select('b.typebadge as type, COUNT(b.id) as count')
//         ->groupBy('b.typebadge')
//         ->getQuery()
//         ->getResult();
// }
public function getNbDiamant() {
 
    return $this->createQueryBuilder('m')

    ->select('COUNT(m.id)')
    ->where('m.type = :type')
    ->setParameter('type', 'Diamant')
    ->getQuery()
    ->getSingleScalarResult();

}
public function getNbSilver() {
 
    return $this->createQueryBuilder('m')

    ->select('COUNT(m.id)')
    ->where('m.type = :type')
    ->setParameter('type', 'Silver')
    ->getQuery()
    ->getSingleScalarResult();

}
public function getNbVIP() {
 
    return $this->createQueryBuilder('m')

    ->select('COUNT(m.id)')
    ->where('m.type = :type')
    ->setParameter('type', 'VIP')
    ->getQuery()
    ->getSingleScalarResult();

}

    public function countBadgesByType(): array
    {
        return $this->createQueryBuilder('b')
            ->select('b.typebadge, COUNT(b.id) as count')
            ->groupBy('b.typebadge')
            ->getQuery()
            ->getResult();
    }


}
