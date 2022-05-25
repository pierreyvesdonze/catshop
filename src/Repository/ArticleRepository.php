<?php

namespace App\Repository;

use App\Entity\Article;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Article|null find($id, $lockMode = null, $lockVersion = null)
 * @method Article|null findOneBy(array $criteria, array $orderBy = null)
 * @method Article[]    findAll()
 * @method Article[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ArticleRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Article::class);
    }

    /**
     * @return Article[] Returns an array of Article objects
     */
    public function findBestSellers()
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.isBestSeller = 1')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult();
    }

    /**
     * @return Article[] Returns an array of Article objects
     */
    public function findByCheapest()
    {
        return $this->findBy(array(), array('price' => 'ASC'));
    }

    /**
     * @return Article[] Returns an array of Article objects
     */
    public function findByExpensivest()
    {
        return $this->findBy(array(), array('price' => 'DESC'));
    }
}
