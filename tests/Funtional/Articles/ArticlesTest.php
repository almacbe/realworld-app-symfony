<?php

namespace App\Tests\Functional\Articles;

use App\Entity\Article;
use App\Entity\User;
use App\Repository\ArticleRepository;
use App\Repository\UserRepository;
use App\Tests\ObjectMother\ArticleMother;
use App\Tests\ObjectMother\UserMother;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class ArticlesTest extends WebTestCase
{
    private KernelBrowser $client;
    private User $user;

    protected function setUp(): void
    {
        $this->client = static::createClient();
        /** @var UserRepository $repository */
        $repository = $this->getContainer()->get(UserRepository::class);
        $this->user = UserMother::anUser();
        $repository->save($this->user);
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
                ],
            ],
            json_decode($client->getResponse()->getContent(), true)
        );
    }

    public function testGetGloballyWithZeroResults()
    {
        $this->client->request(
            'GET',
            '/api/articles'
        );

        $this->assertResponseIsSuccessful();
        $this->assertEquals(
            [
                'articles' => [],
                'articlesCount' => 0,
            ],
            json_decode($this->client->getResponse()->getContent(), true)
        );
    }

    public function testGetGloballyWithOneResult()
    {
        /** @var ArticleRepository $repository */
        $repository = $this->getContainer()->get(ArticleRepository::class);
        $repository->save(ArticleMother::anArticle($this->user));

        $this->client->request(
            'GET',
            '/api/articles'
        );

        $this->assertResponseIsSuccessful();
        $this->assertEquals(
            [
                'articles' => [
                    [
                        'slug' => '/title',
                        'title' => 'title',
                        'description' => 'description',
                        'body' => 'body',
                        'tagList' => [
                            'string',
                        ],
                        'createdAt' => '2021-01-01T00:01:00+0000',
                        'updatedAt' => '2021-01-01T00:01:00+0000',
                        'favorited' => true,
                        'favoritesCount' => 0,
                        'author' => [
                            'username' => 'username',
                            'bio' => 'string',
                            'image' => 'string',
                            'following' => true,
                        ],
                    ],
                ],
                'articlesCount' => 1,
            ],
            json_decode($this->client->getResponse()->getContent(), true)
        );
    }
}
