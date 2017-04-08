<?php

namespace Tests\AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class DefaultControllerTest extends WebTestCase
{
    public function testBlogJson()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/blog.json');

        $this->assertEquals(200, $client->getResponse()->getStatusCode());
    }

    public function testPostJson()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/post/1.json');

        $this->assertEquals(200, $client->getResponse()->getStatusCode());
    }

    public function testBlogHtml()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/blog.html');

        $this->assertEquals(200, $client->getResponse()->getStatusCode());
    }

    public function testSearchHtml()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/search.html?query=and');

        $this->assertEquals(200, $client->getResponse()->getStatusCode());
    }

    public function testSearch()
    {
        $client = static::createClient();

        $query = 'and';
        $crawler = $client->request('GET', '/search.json?query=' . $query);

        $data = json_decode($client->getResponse()->getContent(), true);

        $this->assertNotEmpty($data);
        $firstPost = current($data);

        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        $this->assertArrayHasKey('title', $firstPost);
        $this->assertArrayHasKey('body', $firstPost);
    }
}
