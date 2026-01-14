<?php

namespace App\Repository;

use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\PasswordUpgraderInterface;

/**
 * @extends ServiceEntityRepository<User>
* @implements PasswordUpgraderInterface<User>
 *
 * @method User|null find($id, $lockMode = null, $lockVersion = null)
 * @method User|null findOneBy(array $criteria, array $orderBy = null)
 * @method User[]    findAll()
 * @method User[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UserRepository extends ServiceEntityRepository implements PasswordUpgraderInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, User::class);
    }

    /**
     * Used to upgrade (rehash) the user's password automatically over time.
     */
    public function upgradePassword(PasswordAuthenticatedUserInterface $user, string $newHashedPassword): void
    {
        if (!$user instanceof User) {
            throw new UnsupportedUserException(sprintf('Instances of "%s" are not supported.', $user::class));
        }

        $user->setPassword($newHashedPassword);
        $this->getEntityManager()->persist($user);
        $this->getEntityManager()->flush();
    }
    public function advancedSearch(string $query): array
{
    return $this->createQueryBuilder('u')
        ->andWhere('u.username LIKE :query OR u.email LIKE :query OR u.firstname LIKE :query OR u.lastname LIKE :query OR u.tel LIKE :query OR u.address LIKE :query OR u.role LIKE :query')
        ->setParameter('query', '%' . $query . '%')
        ->getQuery()
        ->getResult();
}
public function findAllSortedBy(string $criteria): array
{
    return $this->createQueryBuilder('u')
        ->orderBy('u.' . $criteria, 'ASC')
        ->getQuery()
        ->getResult();
}
public function findBySearchQuery(string $searchQuery): array
    {
        return $this->createQueryBuilder('u')
            ->andWhere('u.username LIKE :query OR u.email LIKE :query OR u.firstname LIKE :query OR u.lastname LIKE :query OR u.tel LIKE :query OR u.address LIKE :query OR u.role LIKE :query')
            ->setParameter('query', '%' . $searchQuery . '%')
            ->getQuery()
            ->getResult();
    }

   
    public function countUsersWithRoleUser(): int
    {
        $query = $this->createQueryBuilder('u')
            ->select('COUNT(u.iduser) as user_count')
            ->where('u.role LIKE :role')
            ->setParameter('role', '%ROLE_USER%')
            ->getQuery();

        return (int)$query->getSingleScalarResult();
    }
    public function findReclamationsByString($searchString)
{
    return $this->createQueryBuilder('r')
        ->where('r.username LIKE :search')
        ->setParameter('search', '%' . $searchString . '%')
        ->getQuery()
        ->getResult();
}
public function countUsersByEtat(string $etat): int
{
    return $this->createQueryBuilder('u')
        ->select('COUNT(u.iduser)')
        ->where('u.etat = :etat')
        ->setParameter('etat', $etat)
        ->getQuery()
        ->getSingleScalarResult();
}
//    /**
//     * @return User[] Returns an array of User objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('u')
//            ->andWhere('u.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('u.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?User
//    {
//        return $this->createQueryBuilder('u')
//            ->andWhere('u.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
public function find($idrec, $lockMode = null, $lockVersion = null): ?User
    {
        return parent::find($idrec, $lockMode, $lockVersion);
    }
}
