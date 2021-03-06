<?php

namespace App\Repository;

use App\Entity\Movie;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Movie|null find($id, $lockMode = null, $lockVersion = null)
 * @method Movie|null findOneBy(array $criteria, array $orderBy = null)
 * @method Movie[]    findAll()
 * @method Movie[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class MoviesRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Movie::class);
    }


    /**
    * @param $tmdb_id
    * @return Movie[]
    */
    public function checkDB($tmdb_id): array
    {
      // automatically knows to select Products
      // the "p" is an alias you'll use in the rest of the query
      $qb = $this->createQueryBuilder('m')
      ->andWhere('m.tmdb_id = :tmdb_id')
      ->setParameter('tmdb_id', $tmdb_id)
      ->getQuery();

      return $qb->execute();

      // to get just one result:
      // $product = $qb->setMaxResults(1)->getOneOrNullResult();
    }

    
//    /**
//     * @return Movies[] Returns an array of Movies objects
//     */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('m')
            ->andWhere('m.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('m.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Movies
    {
        return $this->createQueryBuilder('m')
            ->andWhere('m.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
