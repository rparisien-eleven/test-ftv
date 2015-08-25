<?php

namespace ApiRestBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use ApiRestBundle\Entity\Article;

class LoadArticleData implements FixtureInterface
{
    static public $articles = array();

    /**
     * {@inheritDoc}
     */
    public function load(ObjectManager $manager)
    {
        $article = new Article();
        $article->setTitle('article 1');
        $article->setLeading('il est bien');
        $article->setBody('aaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa');
        $article->setCreatedBy('toto');

        $article2 = new Article();
        $article2->setTitle('article 2');
        $article2->setLeading('il est mieux');
        $article2->setBody('bbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbb');
        $article2->setCreatedBy('titi');

        $article3 = new Article();
        $article3->setTitle('article 3');
        $article3->setLeading('il est top');
        $article3->setBody('CCCCCCCCCCCCCC');
        $article3->setCreatedBy('tata');

        $manager->persist($article);
        $manager->persist($article2);
        $manager->persist($article3);
        $manager->flush();

        self::$articles[] = $article;
        self::$articles[] = $article2;
        self::$articles[] = $article3;
    }
}