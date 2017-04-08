<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Post;
use Doctrine\ORM\EntityRepository;
use FOS\RestBundle\Request\ParamFetcher;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use FOS\RestBundle\Controller\Annotations\View;
use FOS\RestBundle\Controller\Annotations\Get;
use FOS\RestBundle\Controller\Annotations\QueryParam;

class BlogController extends Controller
{
    /**
     * @Get("/post.{_format}", name="blog_index")
     * @View()
     */
    public function indexAction()
    {
        $posts = $this->getDoctrine()
            ->getRepository('AppBundle:Post')
            ->findAll();

        return $posts;
    }

    /**
     * @Get("/post/{id}.{_format}", name="blog_post")
     * @View()
     */
    public function postAction(int $id)
    {
        $post = $this->getDoctrine()
            ->getRepository('AppBundle:Post')
            ->find($id);

        return $post;
    }

    /**
     * @QueryParam(name="search", requirements="\w+", description="Search results page")
     * @Get("/post.{_format}", name="blog_search")
     * @View()
     */
    public function searchAction(ParamFetcher $paramFetcher)
    {
        /**
         * in order to have proper search results:
         * todo: create an index, with transformed data
         * todo: run queries against it
         * todo: relevance
         */
        $query = strtolower($paramFetcher->get('search'));

        $em = $this->getDoctrine()->getManager();

        $posts = $em->getRepository("AppBundle:Post")->createQueryBuilder('p')
            ->where('p.title LIKE :query')
            ->andWhere('p.body LIKE :query')
            ->setParameter('query', '%' . $query . '%')
            ->getQuery()
            ->getResult();

        return $posts;
    }
}
