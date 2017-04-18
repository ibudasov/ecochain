<?php

namespace AppBundle\Repository;

use Knp\Bundle\PaginatorBundle\Pagination\SlidingPagination;
use Knp\Component\Pager\Pagination\PaginationInterface;
use Knp\Component\Pager\Paginator;

class PostRepository extends \Doctrine\ORM\EntityRepository
{
    // todo: move to params
    const POSTS_PER_PAGE = 3;

    public function getLiveSearchResults(string $query): array
    {
        $dql = $this->getEntityManager()
            ->createQueryBuilder()
            ->select('p')
            ->from('AppBundle:Post', 'p')
            ->where('p.title LIKE :query')
            ->orWhere('p.body LIKE :query')
            ->setParameter('query', '%' . $query . '%')
            ->setMaxResults(self::POSTS_PER_PAGE)
            ->getQuery();

        return $dql->getResult();
    }

    /**
     * in order to have proper search results:
     * todo: create an index, with transformed data
     * todo: run queries against it
     * todo: relevance
     */
    public function getPaginatedSearchResults(string $query, Paginator $paginator, int $page): PaginationInterface
    {
        $query = $this->getEntityManager()
            ->createQueryBuilder()
            ->select('p')
            ->from('AppBundle:Post', 'p')
            ->where('p.title LIKE :query')
            ->orWhere('p.body LIKE :query')
            ->setParameter('query', '%' . $query . '%')
            ->getQuery();

        return $paginator->paginate($query, $page, self::POSTS_PER_PAGE);
    }

    public function getPaginated(Paginator $paginator, int $page): PaginationInterface
    {
        $em = $this->getEntityManager();
        $dql = "SELECT p FROM AppBundle:Post p";
        $query = $em->createQuery($dql);

        return $paginator->paginate($query, $page, self::POSTS_PER_PAGE);
    }
}
