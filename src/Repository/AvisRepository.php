<?php

namespace App\Repository;

use App\Entity\Avis;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;


/**
 * @extends ServiceEntityRepository<Avis>
 *
 * @method Avis|null find($id, $lockMode = null, $lockVersion = null)
 * @method Avis|null findOneBy(array $criteria, array $orderBy = null)
 * @method Avis[]    findAll()
 * @method Avis[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class AvisRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Avis::class);
    }

//    /**
//     * @return Avis[] Returns an array of Avis objects
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

//    public function findOneBySomeField($value): ?Avis
//    {
//        return $this->createQueryBuilder('a')
//            ->andWhere('a.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }

public function findByDate(\DateTimeInterface $date)
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.dateavis = :date')
            ->setParameter('date', $date)
            ->getQuery()
            ->getResult();
    }

public function advancedSearchQuery($username, $restaurantName, $date)
{
    $queryBuilder = $this->createQueryBuilder('a')
        ->leftJoin('a.user', 'u')
        ->leftJoin('a.restaurant', 'r');

    if ($username !== null) {
        $queryBuilder->andWhere('u.username = :username')
            ->setParameter('username', $username);
    }

    if ($restaurantName !== null) {
        $queryBuilder->andWhere('r.nom = :restaurant_name')
            ->setParameter('restaurant_name', $restaurantName);
    }

    if ($date !== null) {
        $queryBuilder->andWhere('a.dateavis >= :date')
            ->setParameter('date', new \DateTime($date)); 
    }

    return $queryBuilder->getQuery()->getResult();
}
public function findByCriteria($restaurantName, $username, $date)
{
    $qb = $this->createQueryBuilder('a')
        ->leftJoin('a.user', 'u')
        ->leftJoin('a.restaurant', 'r');

    if ($restaurantName) {
        $qb->andWhere('r.nom LIKE :restaurant')
           ->setParameter('restaurant', '%'.$restaurantName.'%');
    }

    if ($username) {
        $qb->andWhere('u.username LIKE :username')
           ->setParameter('username', '%'.$username.'%');
    }

    if ($date) {
        $qb->andWhere('a.dateavis = :date')
           ->setParameter('date', new \DateTime($date));
    }

    return $qb->getQuery()->getResult();
}

public function findByAdvancedCriteria($restaurantName, $username, $date)
{
    $queryBuilder = $this->createQueryBuilder('a');
    // Vous pouvez commencer par ajouter des conditions pour chaque critère si ces derniers sont renseignés
    if ($restaurantName !== null) {
        $queryBuilder
            ->leftJoin('a.restaurant', 'r')
            ->andWhere('r.nom LIKE :restaurantName')
            ->setParameter('restaurantName', '%' . $restaurantName . '%');
    }

    if ($username !== null) {
        $queryBuilder
            ->leftJoin('a.user', 'u')
            ->andWhere('u.username LIKE :username')
            ->setParameter('username', '%' . $username . '%');
    }

    if ($date !== null) {
        $queryBuilder
            ->andWhere('a.dateavis = :date')
            ->setParameter('date', $date);
    }

    // Ajoutez d'autres conditions pour les autres critères si nécessaire

    return $queryBuilder->getQuery()->getResult();
}

public function findByCriteriaWithOrder($restaurantName, $username, $date, $orderByField = null, $order = 'ASC')
{
    $queryBuilder = $this->createQueryBuilder('avi');

    // Ajoutez vos conditions de recherche ici
    if ($restaurantName) {
        $queryBuilder->andWhere('avi.restaurant.nom LIKE :restaurant')
            ->setParameter('restaurant', '%'.$restaurantName.'%');
    }
    if ($username) {
        $queryBuilder->andWhere('avi.user.username LIKE :username')
            ->setParameter('username', '%'.$username.'%');
    }
    if ($date) {
        $queryBuilder->andWhere('avi.dateavis = :date')
            ->setParameter('date', $date);
    }

    // Gérez l'ordre de tri
    if ($orderByField) {
        $queryBuilder->orderBy('avi.' . $orderByField, $order);
    }

    return $queryBuilder->getQuery()->getResult();
}
public function findTopThreeMostViewedAvis(): array
{
    return $this->createQueryBuilder('a')
        ->orderBy('a.nbvue', 'DESC')
        ->setMaxResults(3)
        ->getQuery()
        ->getResult();
}

}
