<?php

namespace AppBundle\Repository;

class PostRepository extends \Doctrine\ORM\EntityRepository
{
    /**
     * in order to have proper search results:
     * todo: create an index, with transformed data
     * todo: run queries against it
     * todo: relevance
     */
    public function getSearchResults(string $query): array
    {
        return $this->getEntityManager()
            ->createQueryBuilder()
            ->select('p')
            ->from('AppBundle:Post', 'p')
            ->where('p.title LIKE :query')
            ->orWhere('p.body LIKE :query')
            ->setParameter('query', '%' . $query . '%')
            ->getQuery()
            ->getResult();
    }
}
