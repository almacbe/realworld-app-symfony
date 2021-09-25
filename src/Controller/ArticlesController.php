<?php

namespace App\Controller;

use App\Entity\Article;
use App\Repository\ArticleRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ArticlesController extends AbstractController
{
    /**
     * @Route("/articles", name="create_article", methods={"POST"})
     */
    public function create(Request $request, ArticleRepository $articleRepository): Response
    {
        $this->denyAccessUnlessGranted('ROLE_USER');
        $articleData = json_decode($request->getContent(), true)['article'];

        $article = new Article();
        $article
            ->setTitle($articleData['title'])
            ->setDescription($articleData['description'])
            ->setBody($articleData['body'])
            ->setAuthor($this->getUser())
            ->setCreatedAt(\DateTimeImmutable::createFromFormat('Y-m-d H:i', '2021-01-01 00:01'))
            ->setUpdatedAt(\DateTimeImmutable::createFromFormat('Y-m-d H:i', '2021-01-01 00:01'))
            ->setSlug('/title')
        ;

        // TODO: Handle tags
        // TODO: Handle favorites

        $articleRepository->save($article);

        return $this->json([
            'article' => [
                'title' => $article->getTitle(),
                'slug' => $article->slug(),
                'body' => $article->getBody(),
                'createdAt' => $article->createdAt()->format(DATE_ISO8601),
                'updatedAt' => $article->updatedAt()->format(DATE_ISO8601),
                'description' => $article->getDescription(),
                'tagList' => [],
                'author' => $article->author(),
                'favorited' => '',
                'favoritesCount' => 0,
            ]
        ]);
    }

    /**
     * @Route("/articles", name="get_globaly", methods={"GET"})
     */
    public function getGlobally(ArticleRepository $articleRepository)
    {
        $articles = $articleRepository->findAll();
        $serializedArticles = [];
        foreach ($articles as $article) {
            $serializedArticles[] = [
                'slug' => $article->slug(),
                'title' => $article->getTitle(),
                'description' => $article->getDescription(),
                'body' => $article->getBody(),
            // TODO: handle tags
                'tagList' => [
                    'string',
                ],
                'createdAt' => $article->createdAt()->format(\DateTimeImmutable::ISO8601),
                'updatedAt' => $article->updatedAt()->format(DATE_ISO8601),
                'favorited' => true,
                'favoritesCount' => 0,
                'author' => [
                    'username' => $article->author(),
                // TODO: handle relationship
                    'bio' => 'string',
                    'image' => 'string',
                    'following' => true,
                ],
            ];
        }

        return $this->json([
           'articles' => $serializedArticles,
            'articlesCount' => count($serializedArticles),
        ]);
    }
}
