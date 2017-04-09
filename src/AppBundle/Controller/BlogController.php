<?php

namespace AppBundle\Controller;

use AppBundle\Repository\PostRepository;
use FOS\RestBundle\Request\ParamFetcher;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use FOS\RestBundle\Controller\Annotations\View;
use FOS\RestBundle\Controller\Annotations\Get;
use FOS\RestBundle\Controller\Annotations\QueryParam;

class BlogController extends Controller
{
    /**
     * @Get("/blog.{_format}", name="front_blog")
     * @Get("/post.{_format}", name="api_get_post")
     * @View()
     */
    public function indexAction(): array
    {
        return [
            'posts' => $this->getDoctrine()->getRepository('AppBundle:Post')->findAll()
        ];
    }

    /**
     * @Get("/post/{id}.{_format}", name="api_get_post_id")
     * @Get("/read/{id}.{_format}", name="front_post")
     * @View()
     */
    public function viewAction(int $id): array
    {
        return [
            'post' => $this->getDoctrine()->getRepository('AppBundle:Post')->find($id)
        ];
    }

    /**
     * @QueryParam(name="search", requirements="\w+", description="Search results page")
     * @Get("/post.{_format}", name="api_post_search")
     * @Get("/search.{_format}", name="front_search")
     * @View()
     */
    public function searchAction(ParamFetcher $paramFetcher): array
    {
        $query = $paramFetcher->get('search');

        return [
            'posts' => $this->getDoctrine()->getRepository('AppBundle:Post')->getSearchResults($query),
            'query' => $query
        ];
    }
}
