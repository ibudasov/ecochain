<?php

namespace Tests\AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class DefaultControllerTest extends WebTestCase
{
    #######################################################################
    #                           API
    #######################################################################

    public function testICanGetAllThePosts()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/post');

        $this->assertEquals(200, $client->getResponse()->getStatusCode());
    }

    public function testICanGetOnePost()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/post/1');

        $this->assertEquals(200, $client->getResponse()->getStatusCode());
    }

    public function testICanFindAPost()
    {
        $client = static::createClient();

        $query = 'and';
        $crawler = $client->request('GET', '/post/search.json?query=' . $query);

        $this->assertEquals(200, $client->getResponse()->getStatusCode());

        $data = json_decode($client->getResponse()->getContent(), true);

        $this->assertNotEmpty($data);
        $firstPost = current($data['posts']);
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
        $content = $client->getResponse()->getContent();
        $this->assertTrue(is_int(stripos($content, '<html>')));
    }



    public function testBlog()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/blog.html');

        $this->assertEquals(200, $client->getResponse()->getStatusCode());

        $content = $client->getResponse()->getContent();
        $this->assertTrue(is_int(stripos($content, '<html>')));
    }

    public function testSearchHtml()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/search.html?query=and');

        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        $content = $client->getResponse()->getContent();
        $this->assertTrue(is_int(stripos($content, '<html>')));
    }

}
