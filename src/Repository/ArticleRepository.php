<?php

namespace App\Repository;

use App\Entity\Article;
use App\Entity\Partials\ArticleData;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class ArticleRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Article::class);
    }

    public function getPartialById(int $id): ?Article
    {
        $qb = $this->createQueryBuilder('a');
        $qb
            ->select('PARTIAL a.{id, title}')
            ->where('a.id = :id')
            ->setParameter('id', $id);

        return $qb->getQuery()->getOneOrNullResult();
    }

    public function getLastByCategory(int $categoryId, int $limit): array
    {
        $qb = $this->createQueryBuilder('a');
        $qb
            ->where('a.category = :categoryId')
            ->setParameter('categoryId', $categoryId)
            ->orderBy('a.id', 'DESC')
            ->setMaxResults($limit);

        return $qb->getQuery()->getResult();
    }

    public function getLastPartialsByCategory(int $categoryId, int $limit): array
    {
        $qb = $this->createQueryBuilder('a');
        $qb
            ->select('PARTIAL a.{id, title}')
            ->where('a.category = :categoryId')
            ->setParameter('categoryId', $categoryId)
            ->orderBy('a.id', 'DESC')
            ->setMaxResults($limit);

        return $qb->getQuery()->getResult();
    }

    /**
     * @param int $limit
     * @return ArticleData[]
     */
    public function getLastArticlesData(int $limit): array
    {
        $qb = $this->createQueryBuilder('a');
        $qb
            ->select(sprintf('NEW %s(a.id, a.title, c.name)', ArticleData::class))
            ->join('a.category', 'c')
            ->orderBy('a.id', 'DESC')
            ->setMaxResults($limit);

        return $qb->getQuery()->getResult();
    }

    /**
     * @return Article[]|array
     */
    public function getPublished(): array
    {
        $qb = $this->createQueryBuilder('a');
        $qb
            ->where('a.publishedAt < :now')
            ->setParameter('now', new \DateTime());

        return $qb->getQuery()->getResult();
    }
}
