<?php

namespace App\Repository;

use App\Entity\WeatherDate;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<WeatherDate>
 *
 * @method WeatherDate|null find($id, $lockMode = null, $lockVersion = null)
 * @method WeatherDate|null findOneBy(array $criteria, array $orderBy = null)
 * @method WeatherDate[]    findAll()
 * @method WeatherDate[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class WeatherDateRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, WeatherDate::class);
    }

//    /**
//     * @return WeatherDate[] Returns an array of WeatherDate objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('w')
//            ->andWhere('w.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('w.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?WeatherDate
//    {
//        return $this->createQueryBuilder('w')
//            ->andWhere('w.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
