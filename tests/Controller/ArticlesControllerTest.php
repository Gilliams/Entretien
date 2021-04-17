<?php

namespace App\Tests\Controller;

use App\Tests\Traits\NeedLogin;
use Symfony\Component\HttpFoundation\Response;
use Liip\TestFixturesBundle\Test\FixturesTrait;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class ArticlesControllerTest extends WebTestCase
{

    use FixturesTrait;
    use NeedLogin;

    public function testHomePage(){
        $client = static::createClient();
        $client->request('GET','/');

        $this->assertResponseStatusCodeSame(Response::HTTP_OK);
    }

    public function testValidArticle(){
        $client = static::createClient();
        $users = $this->loadFixtureFiles([__DIR__ . '\users.yaml']);
        $this->login($client, $users['user_user']);
        $crawler = $client->request('GET', '/articles/creation-article');
        $form = $crawler->selectButton('Créer l\'article')->form([
            'article[title]' => 'Title',
            'article[content]' => 'Un super text',
            'article[imageFile][file]' => 'photo.jpg'
        ]);
        $client->submit($form);
        $this->assertResponseStatusCodeSame(Response::HTTP_OK);
    }
    
    public function testBadUserAccess()
    {
        $client = static::createClient();
        $client->request('GET', '/articles/creation-article');
        $this->assertResponseRedirects('/connexion', 302);
    }

}