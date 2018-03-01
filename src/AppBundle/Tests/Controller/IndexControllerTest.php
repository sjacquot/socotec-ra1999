<?php

namespace AppBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class IndexControllerTest extends WebTestCase
{
    public function testList()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/list');
    }

    public function testGeneratereport()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/generateReport');
    }

    public function testGeneratecert()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/generateCert');
    }

}
