<?php

namespace Tests\AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

/**
 * @link: http://symfony.com/doc/current/routing.html#book-routing-conditions
 * @link: http://symfony.com/doc/current/bundles/FOSRestBundle/7-manual-route-definition.html
 * @link: https://github.com/KnpLabs/KnpPaginatorBundle
 */
class DefaultControllerTest extends WebTestCase
{
    #######################################################################
    #                           API
    #######################################################################

    public function testICanGetAllThePosts()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/post.json');

        $this->assertEquals(200, $client->getResponse()->getStatusCode());

        $this->checkIfJson($client);
    }

    public function testICanGetOnePost()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/post/1.json');

        $this->assertEquals(200, $client->getResponse()->getStatusCode());

        $this->checkIfJson($client);
    }

    public function testICanFindAPost()
    {
        $client = static::createClient();

        $query = 'and';
        $crawler = $client->request('GET', '/post/search.json?query=' . $query);

        $this->assertEquals(200, $client->getResponse()->getStatusCode());

        $data = $this->checkIfJson($client);

        $firstPost = current($data['posts']['items']);
        $this->assertArrayHasKey('title', $firstPost);
        $this->assertArrayHasKey('body', $firstPost);
    }

    #######################################################################
    #                           FRONT
    #######################################################################

    public function testMainPage()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/');

        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        $this->checkIfHtml($client);
    }

    public function testBlog()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/blog.html');

        $this->assertEquals(200, $client->getResponse()->getStatusCode());

        $this->checkIfHtml($client);
    }

    public function testSearchHtml()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/search.html?query=and');

        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        $this->checkIfHtml($client);
    }

    private function checkIfJson($client): array
    {
        $data = json_decode($client->getResponse()->getContent(), true);
        $this->assertNotEmpty($data);
        return $data;
    }

    private function checkIfHtml($client): string
    {
        $content = $client->getResponse()->getContent();
        $this->assertTrue(is_int(stripos($content, '<html>')));
        return $content;
    }

}
