<?php


namespace App\Repository;

use App\Entity\Post;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Post>
 *
 * @method Post|null find($id, $lockMode = null, $lockVersion = null)
 * @method Post|null findOneBy(array $criteria, array $orderBy = null)
 * @method Post[]    findAll()
 * @method Post[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PostRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Post::class);
    }

    /**
     * @return string[] Returns an array of unique categories
     */
    public function findUniqueCategoriesWithCount(): array
{
    $queryBuilder = $this->createQueryBuilder('p')
        ->select('p.categoria, COUNT(p.id) as post_count')
        ->groupBy('p.categoria')
        ->orderBy('p.categoria', 'ASC');

    return $queryBuilder->getQuery()->getResult();
}

    /**
     * @param string $category
     * @return Post[] Returns an array of Post objects filtered by category
     */
    public function findByCategory(string $category): array
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.categoria = :category')
            ->setParameter('category', $category)
            ->orderBy('p.id', 'DESC')
            ->getQuery()
            ->getResult();
    }
}
