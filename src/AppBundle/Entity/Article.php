<?php

namespace AppBundle\Entity;

use Symfony\Component\Validator\Constraints as Assert;

/**
 * Article
 */
class Article
{
    /**
     * @var int
     */
    private $id;

    /**
     * @var string
     *
     * @Assert\Length(
     *     min = 2,
     *     max = 255
     * )
     */
    private $articleName = '';

    /**
     * @var string
     *
     * @Assert\Length(
     *     min = 2,
     *     max = 40,
     *     minMessage = "The SLUG must be at least 2 characters",
     *     maxMessage = "The SLUG must be at most 40 characters"
     * )
     */
    private $articleSlug = '';

    /**
     * @var string
     */
    private $articleTeaser = '';

    /**
     * @var string
     */
    private $articleBody = '';

    /**
     * @var \DateTime
     */
    private $articleCreated = null;

    /** @var \DateTime */
    private $articleUpdated = null;

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @param int $id
     *
     * @return Article
     */
    public function setId(int $id): Article
    {
        $this->id = $id;

        return $this;
    }

    /**
     * @return string
     */
    public function getArticleName(): string
    {
        return $this->articleName;
    }

    /**
     * @param string $articleName
     *
     * @return Article
     */
    public function setArticleName(string $articleName): Article
    {
        $this->articleName = $articleName;

        return $this;
    }

    /**
     * @return string
     */
    public function getArticleSlug(): string
    {
        return $this->articleSlug;
    }

    /**
     * @param string $articleSlug
     *
     * @return Article
     */
    public function setArticleSlug(string $articleSlug): Article
    {
        $this->articleSlug = $articleSlug;

        return $this;
    }

    /**
     * @return string
     */
    public function getArticleBody(): string
    {
        return $this->articleBody;
    }

    /**
     * @param string $articleBody
     *
     * @return Article
     */
    public function setArticleBody(string $articleBody): Article
    {
        $this->articleBody = $articleBody;

        return $this;
    }

    /**
     * @return string
     */
    public function getArticleCreated(): \DateTime
    {
        return $this->articleCreated;
    }

    /**
     * @param \DateTime $articleCreated
     *
     * @return Article
     */
    public function setArticleCreated(\DateTime $articleCreated): Article
    {
        $this->articleCreated = $articleCreated->format('Y-m-d H:i:s');

        return $this;
    }

    /**
     * @return string
     */
    public function getArticleTeaser(): string
    {
        return $this->articleTeaser;
    }

    /**
     * @param string $articleTeaser
     *
     * @return Article
     */
    public function setArticleTeaser(string $articleTeaser): Article
    {
        $this->articleTeaser = $articleTeaser;

        return $this;
    }

    /**
     * @return \DateTime
     */
    public function getArticleUpdated(): \DateTime
    {
        return $this->articleUpdated;
    }

    /**
     * @param \DateTime $articleUpdated
     *
     * @return Article
     */
    public function setArticleUpdated(\DateTime $articleUpdated): Article
    {
        $this->articleUpdated = $articleUpdated;

        return $this;
    }


}

