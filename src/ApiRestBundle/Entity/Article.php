<?php

namespace ApiRestBundle\Entity;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use JMS\Serializer\Annotation\ExclusionPolicy;
use JMS\Serializer\Annotation\Expose;
use JMS\Serializer\Annotation\Exclude;
use JMS\Serializer\Annotation\Groups;
use JMS\Serializer\Annotation\VirtualProperty;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * Article
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="ApiRestBundle\Entity\ArticleRepository")
 * @ORM\HasLifecycleCallbacks()
 *
 */
class Article
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     *
     * @Exclude
     */
    private $id;

    /**
     * @var string
     *
     * @Assert\Length(
     *      min = "1",
     *      max = "100",
     *      minMessage = "Le titre doit être au minimum de {{ limit }} caracteres",
     *      maxMessage = "Le titre doit être au maximum de {{ limit }} caracteres"
     * )
     * @Assert\NotBlank()
     * @ORM\Column(name="title", type="string", length=100, nullable=false)
     *
     * @Groups({"list_articles", "get_article"})
     */
    private $title;

    /**
     * @var string
     *
     * @Assert\Length(
     *      min = "1",
     *      max = "255",
     *      minMessage = "L'accroche doit être au minimum de {{ limit }} caracteres",
     *      maxMessage = "L'accroche doit être au maximum de {{ limit }} caracteres"
     * )
     * @ORM\Column(name="leadingEntry", type="string", length=255, nullable=true)
     *
     * @Groups({"list_articles", "get_article"})
     */
    private $leading;

    /**
     * @var string
     *
     * @Assert\Length(
     *      min = "1",
     *      max = "255",
     *      minMessage = "Le corps doit être au minimum de {{ limit }} caracteres",
     *      maxMessage = "Le corps doit être au maximum de {{ limit }} caracteres"
     * )
     * @ORM\Column(name="body", type="string", length=2048, nullable=true)
     *
     * @Groups({"get_article"})
     */
    private $body;

    /**
     * @var \DateTime
     *
     * @Assert\DateTime()
     *
     * @ORM\Column(name="created_at", type="datetime", nullable=false)
     *
     * @Groups({"list_articles", "get_article"})
     */
    private $createdAt;

    /**
     * @var string
     *
     * @Assert\Length(
     *      min = "1",
     *      max = "255",
     *      minMessage = "L'auteur doit être au minimum de {{ limit }} caracteres",
     *      maxMessage = "L'auteur doit être au maximum de {{ limit }} caracteres"
     * )
     *
     * @Gedmo\Slug(fields={"title"}, updatable=false)
     * @ORM\Column(name="slug", type="string", length=255, nullable=false)
     *
     * @Groups({"list_articles", "get_article"})
     *
     */
    private $slug;

    /**
     * @var string
     *
     * @Assert\Length(
     *      min = "1",
     *      max = "50",
     *      minMessage = "L'auteur doit être au minimum de {{ limit }} caracteres",
     *      maxMessage = "L'auteur doit être au maximum de {{ limit }} caracteres"
     * )
     * @ORM\Column(name="created_by", type="string", length=50, nullable=false)
     *
     * @Groups({"list_articles", "get_article"})
     */
    private $createdBy;

    /**
     * @ORM\PrePersist
     */
    public function setDate()
    {
        $this->createdAt = new \DateTime('now');
    }

    /**
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set title
     *
     * @param string $title
     *
     * @return Article
     */
    public function setTitle($title)
    {
        $this->title = $title;

        return $this;
    }

    /**
     * Get title
     *
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * Set leading
     *
     * @param string $leading
     *
     * @return Article
     */
    public function setLeading($leading)
    {
        $this->leading = $leading;

        return $this;
    }

    /**
     * Get leading
     *
     * @return string
     */
    public function getLeading()
    {
        return $this->leading;
    }

    /**
     * Set body
     *
     * @param string $body
     *
     * @return Article
     */
    public function setBody($body)
    {
        $this->body = $body;

        return $this;
    }

    /**
     * Get body
     *
     * @return string
     */
    public function getBody()
    {
        return $this->body;
    }

    /**
     * Set createdAt
     *
     * @param \DateTime $createdAt
     *
     * @return Article
     */
    public function setCreatedAt($createdAt)
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    /**
     * Get createdAt
     *
     * @return \DateTime
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    /**
     * Set slug
     *
     * @param string $slug
     *
     * @return Article
     */
    public function setSlug($slug)
    {
        $this->slug = $slug;

        return $this;
    }

    /**
     * Get slug
     *
     * @return string
     */
    public function getSlug()
    {
        return $this->slug;
    }

    /**
     * Set createdBy
     *
     * @param string $createdBy
     *
     * @return Article
     */
    public function setCreatedBy($createdBy)
    {
        $this->createdBy = $createdBy;

        return $this;
    }

    /**
     * Get createdBy
     *
     * @return string
     */
    public function getCreatedBy()
    {
        return $this->createdBy;
    }
}
