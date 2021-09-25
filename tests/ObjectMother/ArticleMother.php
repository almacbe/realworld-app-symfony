<?php

namespace App\Tests\ObjectMother;

use App\Entity\Article;
use App\Entity\User;

final class ArticleMother
{
    public static function anArticle(User $user): Article
    {
        $articleData = [
            'title' => 'title',
            'slug' => '/title',
            'body' => 'body',
            'createdAt' => '2021-01-01T00:01:00+0000',
            'updatedAt' => '2021-01-01T00:01:00+0000',
            'description' => 'description',
        ];
        $article = new Article();
        $article
            ->setTitle($articleData['title'])
            ->setDescription($articleData['description'])
            ->setBody($articleData['body'])
            ->setAuthor($user)
            ->setCreatedAt(\DateTimeImmutable::createFromFormat('Y-m-d H:i', '2021-01-01 00:01'))
            ->setUpdatedAt(\DateTimeImmutable::createFromFormat('Y-m-d H:i', '2021-01-01 00:01'))
            ->setSlug($articleData['slug'])
        ;

        return $article;
    }
}
