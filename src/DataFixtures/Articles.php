<?php

namespace App\DataFixtures;

use App\Entity\Article;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Ramsey\Uuid\Uuid;
use Symfony\Component\String\Slugger\SluggerInterface;

class Articles extends Fixture
{
    private SluggerInterface $slugger;

    public function __construct(SluggerInterface $slugger)
    {
        $this->slugger = $slugger;
    }

    public function load(ObjectManager $manager): void
    {
        $jsonFile = __DIR__ . '/articles.json';
        $articlesData = json_decode(file_get_contents($jsonFile), true);

        foreach ($articlesData as $articleData) {
            $article = new Article();
            $article->setTitle($articleData['title']);
            $article->setSlug($this->slugger->slug($articleData['title'])->lower());
            $article->setPublishedAt(new \DateTime($articleData['publishedAt']));
            $article->setContent($articleData['content']);
            $article->setCover($articleData['urlToImage']);

            $manager->persist($article);
        }

        $manager->flush();
    }
}
