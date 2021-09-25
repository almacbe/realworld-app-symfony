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
            [],
            [],
            ['CONTENT_TYPE' => 'application/json'],
            json_encode([
                'username' => $username,
                'password' => $password,
            ])
        );

        $data = json_decode($this->client->getResponse()->getContent(), true);
        $this->assertArrayHasKey('token', $data);

        $this->client->setServerParameter('HTTP_Authorization', sprintf('Token %s', $data['token']));

        return $this->client;
    }

    public function testCreate()
    {
        $client = $this->createAuthenticatedClient();
        $client->request(
            'POST',
            '/api/articles',
            [],
            [],
            [],
            json_encode([
                'article' => [
                    'title' => 'title',
                    'description' => 'description',
                    'body' => 'body',
                    'tagList' => [
                        'tag',
                    ],
                ],
            ])
        );

        $this->assertResponseIsSuccessful();
        $this->assertEquals(
            [
                'article' => [
                    'title' => 'title',
                    'slug' => '/title',
                    'body' => 'body',
                    'createdAt' => '2021-01-01T00:01:00+0000',
                    'updatedAt' => '2021-01-01T00:01:00+0000',
                    'description' => 'description',
                    'tagList' => [],
                    'author' => 'username',
                    'favorited' => '',
                    'favoritesCount' => 0,
                ]
            ],
            json_decode($client->getResponse()->getContent(), true)
        );
    }
}
