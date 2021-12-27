<?php

namespace App\Repository;

use App\Entity\TagsProduct;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method TagsProduct|null find($id, $lockMode = null, $lockVersion = null)
 * @method TagsProduct|null findOneBy(array $criteria, array $orderBy = null)
 * @method TagsProduct[]    findAll()
 * @method TagsProduct[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TagsProductRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, TagsProduct::class);
    }

    // /**
    //  * @return TagsProduct[] Returns an array of TagsProduct objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('t')
            ->andWhere('t.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('t.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?TagsProduct
    {
        return $this->createQueryBuilder('t')
            ->andWhere('t.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
