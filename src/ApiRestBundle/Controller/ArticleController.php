<?php

namespace ApiRestBundle\Controller;

use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\View\View;
use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\Util\Codes;

use Symfony\Component\Form\FormTypeInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;

use ApiRestBundle\Entity\Article;
use ApiRestBundle\Form\ArticleType;

class ArticleController extends FOSRestController
{
    /**
     * Get a list of articles
     *
     * @return array
     * @Rest\View(serializerGroups={"list_articles"})
     */
    public function getArticlesAction()
    {
        $em = $this->getDoctrine()->getManager();
        $articles = $em->getRepository('ApiRestBundle:Article')->findAll();
        return $articles;
    }

    /**
     * Get an article
     *
     * @Rest\Route("/articles/{id}")
     * @Rest\View(serializerGroups={"get_article"})
     * @ParamConverter("article", class="ApiRestBundle:Article", options={"mapping": {"id": "slug"}})
     *
     * @param Article $article The game object gotten via DoctrineParamConverter
     *
     * @return array
     *
     * @throws NotFoundHttpException when article does not exist
     */
    public function getArticleAction(Article $article)
    {
        return $article;
    }

    /**
     * Presents the form to use to create a new article
     *
     * @Rest\View(templateVar = "form")
     *
     * @return FormTypeInterface
     */
    public function newArticleAction()
    {
        return $this->createForm(new ArticleType());
    }

    /**
     * Handle the form data
     *
     * @param Request $request
     * @param Article $articles
     * @param string  $status
     *
     * @return View|Response
     */
    public function processForm(Request $request, Article $article, $status)
    {
        $form = $this->createForm(new ArticleType(), $article, ['method' => $request->getMethod()]);
        $jsonData = json_decode($request->getContent(), true);
        $articleService = $this->get('article_service');
        $jsonData = $articleService->sanitizeData($jsonData);
        $form->submit($jsonData);
        if ($form->isValid()) {
            $data = $form->getData();
            $isNew = ($status === Codes::HTTP_CREATED) ? true : false;
            $articleService->saveArticle($data, $isNew);
            $routeOptions = [
                'id' => $article->getSlug(),
                '_format' => $request->get('_format')
            ];
            return $this->routeRedirectView('get_article', $routeOptions, $status);
        }

        return $this->view(
            ['form' => $form],
            Codes::HTTP_BAD_REQUEST
        );
    }

    /**
     * Create a new article
     *
     * @param Request $request
     *
     * @return View|Response
     */
    public function postArticleAction(Request $request)
    {
        return $this->processForm($request, new Article(), Codes::HTTP_CREATED);
    }

    /**
     * Delete an article
     *
     * @param int $id The Article id
     *
     * @Rest\View(statusCode=204)
     */
    public function deleteArticleAction($id)
    {
        $articleService = $this->get('article_service');
        $removed = $articleService->removeArticle($id);
        if ($removed === 0) {
            return new Response('Can\'t delete Article', Codes::HTTP_NOT_FOUND);
        }
    }
}