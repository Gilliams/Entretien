<?php

namespace App\Tests\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class ArticlesControllerTest extends WebTestCase
{
    public function testHomePage(){
        $client = static::createClient();
        $client->request('GET','/');

        $this->assertResponseStatusCodeSame(Response::HTTP_OK);
    }
}