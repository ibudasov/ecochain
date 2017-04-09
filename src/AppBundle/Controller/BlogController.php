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
    /** @var PostRepository postRepository */
    private $postRepository;

    public function __construct()
    {
        $this->postRepository = $this->getDoctrine()->getRepository('AppBundle:Post');
    }

    /**
     * @Get("/post.{_format}", name="api_get_post")
     * @Get("/blog", name="front_blog")
     * @View()
     */
    public function indexAction(): array
    {
        return [
            'posts' => $this->postRepository->findAll()
        ];
    }

    /**
     * @Get("/post/{id}.{_format}", name="api_get_post_id")
     * @Get("/read/{id}", name="front_post")
     * @View()
     */
    public function viewAction(int $id): array
    {
        return [
            'post' => $this->postRepository->find($id)
        ];
    }

    /**
     * @QueryParam(name="search", requirements="\w+", description="Search results page")
     * @Get("/post.{_format}", name="api_post_search")
     * @Get("/search", name="front_search")
     * @View()
     */
    public function searchAction(ParamFetcher $paramFetcher): array
    {
        $query = $paramFetcher->get('search');

        return [
            'posts' => $this->postRepository->getSearchResults($query),
            'query' => $query
        ];
    }
}
