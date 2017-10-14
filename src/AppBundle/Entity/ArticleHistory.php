<?php
/**
 * Created by PhpStorm.
 * User: mark.smith
 * Date: 02/10/2017
 * Time: 12:18
 */

namespace AppBundle\Entity;

/**
 * Article History
 */
class ArticleHistory
{
    /**
     * @var int
     */
    private $id;

    /**
     * @var int
     */
    private $articleId;

    /**
     * @var string
     */
    private $articleName;

    /**
     * @var string
     */
    private $articleSlug;

    /**
     * @var string
     */
    private $articleTeaser = '';

    /**
     * @var string
     */
    private $articleBody;

    /**
     * @var \DateTime
     */
    private $articleCreated;

    public function __construct(Article $article)
    {
        $reflectionObj = new \ReflectionClass($article);
        /** @var \ReflectionMethod $method */
        foreach ($reflectionObj->getMethods() as $method) {
            $methodName = $method->getName();
            if (substr($methodName, 0, 3) === 'get') {
                $value = $article->{$methodName}();
                if ($value) {
                    if ($methodName === 'setId') {
                        $this->setArticleId($value);
                    } else {
                        if (method_exists($this, 'set' . substr($methodName, 3))) {
                            $this->{'set' . substr($methodName, 3)}($value);
                        }
                    }
                }
            }
        }
    }

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
     * @return \AppBundle\Entity\ArticleHistory
     */
    public function setId(int $id): ArticleHistory
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
     * @return \AppBundle\Entity\ArticleHistory
     */
    public function setArticleName(string $articleName): ArticleHistory
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
     * @return \AppBundle\Entity\ArticleHistory
     */
    public function setArticleSlug(string $articleSlug): ArticleHistory
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
     * @return \AppBundle\Entity\ArticleHistory
     */
    public function setArticleBody(string $articleBody): ArticleHistory
    {
        $this->articleBody = $articleBody;

        return $this;
    }

    /**
     * @return string
     */
    public function getArticleCreated(): string
    {
        return $this->articleCreated;
    }

    /**
     * @param \DateTime $articleCreated
     *
     * @return \AppBundle\Entity\ArticleHistory
     */
    public function setArticleCreated(\DateTime $articleCreated): ArticleHistory
    {
        $this->articleCreated = $articleCreated->format('Y-m-d H:i:s');

        return $this;
    }

    /**
     * @return int
     */
    public function getArticleId(): int
    {
        return $this->articleId;
    }

    /**
     * @param int $articleId
     *
     * @return ArticleHistory
     */
    public function setArticleId(int $articleId): ArticleHistory
    {
        $this->articleId = $articleId;

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
     * @return ArticleHistory
     */
    public function setArticleTeaser(string $articleTeaser): ArticleHistory
    {
        $this->articleTeaser = $articleTeaser;

        return $this;
    }

}
