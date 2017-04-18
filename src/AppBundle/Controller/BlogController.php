<?php

namespace AppBundle\Controller;

use AppBundle\Repository\PostRepository;
use FOS\RestBundle\Request\ParamFetcher;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use FOS\RestBundle\Controller\Annotations\View;
use FOS\RestBundle\Controller\Annotations\Get;
use FOS\RestBundle\Controller\Annotations\QueryParam;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class BlogController extends Controller
{
    /**
     * Returns the list of posts. In case of frontent it's known as an index page.
     * Returns paginated data, feel free to specify pages.
     *
     * @ApiDoc(
     *     resource=false,
     *     resourceDescription="Operations on posts",
     *     description="Retrieve list of posts.",
     *     statusCodes={
     *         200="Returned when successful"
     *     }
     * )
     *
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
     * Will return a post with specific id or throw an error
     *
     * @ApiDoc(
     *     resource=false,
     *     resourceDescription="Operations on posts",
     *     description="Retrieve a particular post",
     *     statusCodes={
     *         200="Returned when successful",
     *         404="Returned when not found"
     *     }
     * )
     *
     * @Get("/read/{id}.{_format}", name="front_post", requirements={"id": "\d+"}, defaults={"_format" = "html"})
     * @Get("/post/{id}.{_format}", name="api_get_post_id", requirements={"id": "\d+"}, defaults={"_format" = "json"}))
     * @View()
     */
    public function viewAction(int $id): array
    {
        $post = $this->getRepo()->find($id);

        if(empty($post)) {
            throw new NotFoundHttpException('Post not found');
        }

        return [
            'post' => $post
        ];
    }

    /**
     * Returns list of posts, related to a query. Results will be paginated
     *
     * @ApiDoc(
     *     resource=false,
     *     resourceDescription="Operations on posts",
     *     description="Returns list of posts, related to a query",
     *     statusCodes={
     *         200="Returned when successful"
     *     }
     * )
     *
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

    /**
     * Returns list of posts, related to a query. Results will not be paginated, but quite limited
     *
     * @ApiDoc(
     *     resource=false,
     *     resourceDescription="Operations on posts",
     *     description="Returns list of posts, related to a query",
     *     statusCodes={
     *         200="Returned when successful"
     *     }
     * )
     *
     * @QueryParam(name="query", requirements="\w+", description="Search query string")
     * @Get("/post/liveSearch.{_format}", name="api_post_live_search", defaults={"_format" = "json"})
     * @View()
     */
    public function liveSearchAction(ParamFetcher $paramFetcher): array
    {
        $query = $paramFetcher->get('query');

        return [
            'posts' => $this->getRepo()->getLiveSearchResults($query),
            'query' => $query
        ];
    }

    private function getRepo(): PostRepository
    {
        return $this->getDoctrine()->getRepository('AppBundle:Post');
    }
}
