<?php

namespace ApiRestBundle\Service;

use Doctrine\ORM\EntityManager;
use ApiRestBundle\Entity\Article;

class ArticleService
{
    /**
     * @var EntityManager
     */
    protected $em;

    /**
     * @param EntityManager $em
     */
    public function __construct(EntityManager $em)
    {
        $this->em = $em;
    }

    /**
     * Persist an article
     *
     * @param Article $data
     * @param $isNew
     */
    public function saveArticle(Article $data, $isNew)
    {
        if ($isNew === true) {
            $this->em->persist($data);
        }
        $this->em->flush();
    }

    /**
     * Delete an article
     *
     * @param $id
     * @return mixed
     */
    public function removeArticle($id)
    {
        return $this->em->getRepository('ApiRestBundle:Article')->remove($id);
    }

    /**
     * Sanitize data due to serialization
     *
     * @param $data
     * @return mixed
     */
    public function sanitizeData($data)
    {
        if (!empty($data['created_by'])) {
            $data['createdBy'] = $data['created_by'];
            unset($data['created_by']);
        }

        return $data;
    }
}