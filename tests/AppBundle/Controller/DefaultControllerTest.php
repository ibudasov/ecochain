<?php

namespace Tests\AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class DefaultControllerTest extends WebTestCase
{
    public function testMainPage()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/');

        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        $this->checkIfHtml($client);
    }

    private function checkIfHtml($client): string
    {
        $content = $client->getResponse()->getContent();
        $this->assertTrue(is_int(stripos($content, '<html>')));
        return $content;
    }
}
