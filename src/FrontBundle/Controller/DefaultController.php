<?php

namespace FrontBundle\Controller;

use ApiRestBundle\Entity\Article;
use ApiRestBundle\Form\ArticleType;
use FOS\RestBundle\Util\Codes;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class DefaultController extends Controller
{
    public function listAction()
    {
        $clientService = $this->get('client_service');
        $articles = $clientService->get('http://localhost/api/articles.json');
        if (empty($articles)) {
            $articles = [];
        }
        return $this->render('FrontBundle:Default:list.html.twig', array('articles' => $articles));
    }

    public function newAction()
    {
        $form = $this->createForm(new ArticleType());

        return $this->render('FrontBundle:Default:new.html.twig', array(
            'form' => $form->createView(),
        ));
    }

    public function createAction(Request $request)
    {
        $form = $this->createForm(new ArticleType());
        $form->handleRequest($request);
        if ($form->isValid()) {
            $data = $form->getData();
            $serializer = $this->get('serializer');
            $articleSerialized = $serializer->serialize($data, 'json');
            $clientService = $this->get('client_service');
            $response = $clientService->post('http://localhost/api/articles.json', $articleSerialized);
            if ($response->getStatusCode() == Codes::HTTP_CREATED) {
                return $this->redirect($this->generateUrl('front_list'));
            }
        }

        return $this->render('FrontBundle:Default:new.html.twig', array('form' => $form->createView()));
    }

    public function getAction(Request $request)
    {
        $slug = $request->get('slug');
        $clientService = $this->get('client_service');
        $url = sprintf('http://localhost/api/articles/%s.json', $slug);
        $article = $clientService->get($url);
        return $this->render('FrontBundle:Default:get.html.twig', array('article' => $article));
    }

    public function deleteAction(Request $request)
    {
        $slug = $request->get('slug');
        $clientService = $this->get('client_service');
        $url = sprintf('http://localhost/api/articles/%s.json', $slug);
        $clientService->delete($url);
        return $this->redirect($this->generateUrl('front_list'));
    }
}
