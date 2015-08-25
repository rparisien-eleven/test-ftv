<?php

namespace ApiRestBundle\Tests\Controller;

use Liip\FunctionalTestBundle\Test\WebTestCase;
use ApiRestBundle\DataFixtures\ORM\LoadArticleData;

class ArticleControllerTest extends WebTestCase
{
    protected $client;

    protected function setUp()
    {
        $this->client = static::createClient();
        $loadArticleData = new LoadArticleData();
        $loadArticleData->load($this->client->getContainer()->get('doctrine.orm.entity_manager'));
    }

    protected function tearDown()
    {
        LoadArticleData::$articles = [];
        $this->client->getContainer()->get('doctrine.orm.entity_manager')
            ->getRepository('ApiRestBundle:Article')
            ->clearAllArticles();
    }

    protected function assertJsonResponse($response, $statusCode = 200)
    {
        $this->assertEquals(
            $statusCode, $response->getStatusCode(),
            $response->getContent()
        );
        $this->assertTrue(
            $response->headers->contains('Content-Type', 'application/json'),
            $response->headers
        );
    }

    public function testListAction()
    {
        $articles = LoadArticleData::$articles;
        $route =  $this->getUrl('get_articles', ['_format' => 'json']);
        $this->client->request('GET', $route, ['ACCEPT' => 'application/json']);
        $response = $this->client->getResponse();
        $content = $response->getContent();
        $arrayArticles = json_decode($content, true);
        $firstArt = reset($articles);
        $lastArt = end($articles);
        $this->assertJsonResponse($response, 200);
        $this->assertEquals(count($articles), count($arrayArticles));
        $this->assertEquals($firstArt->getTitle(), $arrayArticles[0]['title']);
        $this->assertEquals($firstArt->getLeading(), $arrayArticles[0]['leading']);
        $this->assertFalse(array_key_exists('body', $arrayArticles[0]));
        $this->assertEquals($lastArt->getTitle(), $arrayArticles[2]['title']);
        $this->assertEquals($lastArt->getLeading(), $arrayArticles[2]['leading']);
        $this->assertFalse(array_key_exists('body', $arrayArticles[2]));
    }

    public function testListActionReturnsEmptyList()
    {
        $this->tearDown();
        $route =  $this->getUrl('get_articles', ['_format' => 'json']);
        $this->client->request('GET', $route, ['ACCEPT' => 'application/json']);
        $response = $this->client->getResponse();
        $content = $response->getContent();
        $arrayArticles = json_decode($content, true);
        $this->assertJsonResponse($response, 200);
        $this->assertEmpty($arrayArticles);
    }

    public function testGetAction()
    {
        $articles = LoadArticleData::$articles;
        $article = end($articles);
        $route =  $this->getUrl('get_article', ['id' => $article->getSlug(), '_format' => 'json']);
        $this->client->request('GET', $route, ['ACCEPT' => 'application/json']);
        $response = $this->client->getResponse();
        $content = $response->getContent();
        $articleResult = json_decode($content, true);
        $this->assertJsonResponse($response, 200);
        $this->assertEquals($article->getSlug(), $articleResult['slug']);
        $this->assertEquals($article->getTitle(), $articleResult['title']);
        $this->assertEquals($article->getLeading(), $articleResult['leading']);
        $this->assertEquals($article->getBody(), $articleResult['body']);
        $this->assertEquals($article->getCreatedBy(), $articleResult['created_by']);
    }

    public function testGetActionThrows404()
    {
        $route =  $this->getUrl('get_article', ['id' => '99999999999999', '_format' => 'json']);
        $this->client->request('GET', $route, ['ACCEPT' => 'application/json']);
        $response = $this->client->getResponse();
        $this->assertJsonResponse($response, 404);
    }

    public function testPostPageAction()
    {
        $this->client->request(
            'POST',
            '/api/articles.json',
            [],
            [],
            ['CONTENT_TYPE' => 'application/json'],
            json_encode([
                'title' => 'Un titre',
                'leading' => 'hey',
                'body' => 'AAAAAAAAAAAAAAAAAAAAA',
                'createdBy' => 'aaaaa'
            ])
        );
        $this->assertJsonResponse($this->client->getResponse(), 201);
    }

    public function testPostActionReturns400WithBadParameters()
    {
        $this->client->request(
            'POST',
            '/api/articles.json',
            [],
            [],
            ['CONTENT_TYPE' => 'application/json'],
            json_encode([
                'body' => 'AAAAAAAAAAAAAAAAAAAAA',
                'createdBy' => 'aaaaa'
            ])
        );
        $this->assertJsonResponse($this->client->getResponse(), 400);
    }

    public function testDeleteActionThrows404()
    {
        $route =  $this->getUrl('delete_article', ['id' => '99999999999999', '_format' => 'json']);
        $this->client->request('DELETE', $route, ['ACCEPT' => 'application/json']);
        $response = $this->client->getResponse();
        $this->assertJsonResponse($response, 404);
    }

    public function testDeleteAction()
    {
        $articles = LoadArticleData::$articles;
        $article = end($articles);
        $route =  $this->getUrl('delete_article', ['id' => $article->getSlug(), '_format' => 'json']);
        $this->client->request('DELETE', $route, ['ACCEPT' => 'application/json']);
        $response = $this->client->getResponse();
        $this->assertEquals($response->getStatusCode(), 204);
    }
}