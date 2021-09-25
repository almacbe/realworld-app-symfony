<?php

namespace App\Tests\Functional\Articles;

use App\Repository\UserRepository;
use App\Tests\ObjectMother\UserMother;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class ArticlesTest extends WebTestCase
{
    private KernelBrowser $client;

    protected function setUp(): void
    {
        $this->client = static::createClient();
        /** @var UserRepository $repository */
       $repository = $this->getContainer()->get(UserRepository::class);
       $repository->save(UserMother::aUser());
    }

    protected function createAuthenticatedClient($username = 'user@email.com', $password = 'password'): KernelBrowser
    {
        $this->client->request(
            'POST',
            '/api/users/login',
            array(),
            array(),
            array('CONTENT_TYPE' => 'application/json'),
            json_encode(array(
                'username' => $username,
                'password' => $password,
            ))
        );

        $data = json_decode($this->client->getResponse()->getContent(), true);
        $this->assertArrayHasKey('token', $data);

        $this->client->setServerParameter('HTTP_Authorization', sprintf('Token %s', $data['token']));

        return $this->client;
    }

    public function testCreate()
    {
        $client = $this->createAuthenticatedClient();
//        $this->client->request('POST', '/api/')
    }
}
