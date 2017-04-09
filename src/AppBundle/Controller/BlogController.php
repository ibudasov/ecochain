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
     * @Get("/blog.{_format}", name="front_blog", defaults={"_format" = "html"})
     * @Get("/post.{_format}", name="api_get_post", defaults={"_format" = "json"})
     * @View()
     */
    public function indexAction(Request $request): array
    {
        return [
            'posts' => $this->getRepo()->getPaginated(
                $this->get('knp_paginator'),
                $request->query->getInt('page', 1)
            )
        ];
    }

    /**
     * @Get("/read/{id}.{_format}", name="front_post", requirements={"id": "\d+"}, defaults={"_format" = "html"})
     * @Get("/post/{id}.{_format}", name="api_get_post_id", requirements={"id": "\d+"}, defaults={"_format" = "json"}))
     * @View()
     */
    public function viewAction(int $id): array
    {
        return [
            'post' => $this->getRepo()->find($id)
        ];
    }

    /**
     * @QueryParam(name="query", requirements="\w+", description="Search query string")
     * @QueryParam(name="page", requirements="\d+", description="Page number", default="1")
     * @Get("/search.{_format}", name="front_search", defaults={"_format" = "html"})
     * @Get("/post/search.{_format}", name="api_post_search", defaults={"_format" = "json"})
     * @View()
     */
    public function searchAction(ParamFetcher $paramFetcher): array
    {
        $query = $paramFetcher->get('query');
        $page = $paramFetcher->get('page');

        return [
            'posts' => $this->getRepo()->getPaginatedSearchResults(
                $query,
                $this->get('knp_paginator'),
                $page
            ),
            'query' => $query
        ];
    }

    private function getRepo(): PostRepository
    {
        return $this->getDoctrine()->getRepository('AppBundle:Post');
    }
}
