<?php

namespace App\Tests\Controller;

use App\Tests\Traits\NeedLogin;
use Symfony\Component\HttpFoundation\Response;
use Liip\TestFixturesBundle\Test\FixturesTrait;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class SecurityControllerTest extends WebTestCase
{
    use FixturesTrait;
    use NeedLogin;

    public function testDisplayLogin()
    {
        $client = static::createClient();
        $client->request('GET','/connexion');
        $this->assertResponseStatusCodeSame(Response::HTTP_OK);
    }

    public function testLoginWithBadCredentials()
    {
        $client = static::createClient();
        $crawler = $client->request('GET','/connexion');
        $form = $crawler->selectButton('Se connecter')->form([
            'email' => 'john@doe.fr',
            'password' => 'fakepassword'
        ]);
        $client->submit($form);
        $this->assertResponseRedirects('/connexion');
        $client->followRedirect();
        $this->assertSelectorExists('.alert.alert-danger');
    }

    public function testSuccessfullLogin()
    {
        $client = static::createClient();
        $this->loadFixtureFiles([__DIR__ . '\users.yaml']);
        $csrfToken = $client->getContainer()->get('security.csrf.token_manager')->getToken('authenticate');
        $crawler = $client->request('POST','/connexion', [
            '_csrf_token' => $csrfToken,
            'email' => 'john@doe.fr',
            'password' => 'secret'
        ]);
        $this->assertResponseRedirects('/');
    }

    public function testLetAuthenticateUserAccess()
    {
        $client = static::createClient();
        $users = $this->loadFixtureFiles([__DIR__ . '\users.yaml']);
        $this->login($client, $users['user_user']);
        $client->request('GET', '/');
        $this->assertResponseStatusCodeSame(Response::HTTP_OK);
    }

}