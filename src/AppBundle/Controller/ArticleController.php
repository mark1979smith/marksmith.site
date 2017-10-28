<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Article;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;

class ArticleController extends Controller
{
    /**
     * @Route("/", name="homepage")
     * @Method({"GET"})
     *
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param \Psr\Log\LoggerInterface                  $logger
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function indexAction(Request $request, LoggerInterface $logger): Response
    {
        $data = [];
        /** @var \AppBundle\Utils\Api\Mysql $mysqlApi */
        $mysqlApi = $this->get('app.api.mysql');

        $status = $mysqlApi->read(Article::class);
        $results = unserialize(base64_decode($status['results']));
        $data['articles'] = $results;

        // replace this example code with whatever you need
        return $this->render('default/index.html.twig', $data);
    }

    /**
     * @Route(
     *     "/{slug}{ending_char}",
     *     defaults={"ending_char": ""},
     *     requirements={
     *          "slug": "[a-z0-9\-]+",
     *          "ending_char": "/"
     *     },
     *     name="view-article"
     * )
     * @Method({"GET"})
     *
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param \Psr\Log\LoggerInterface                  $logger
     * @param string                                    $slug
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function viewArticleAction(Request $request, LoggerInterface $logger, string $slug): Response
    {
        $data = [];

        /** @var \AppBundle\Utils\Api\Mysql $api */
        $mysqlApi = $this->get('app.api.mysql');

        $article = new Article();
        $article->setArticleSlug($slug);
        $status = $mysqlApi->read(Article::class, $article);
        $results = unserialize(base64_decode($status['results']));
        if (isset($results[0])) {
            $data['article'] = $results[0];
            return $this->render('article/view-article.html.twig', $data);
        } else {
            throw $this->createNotFoundException('The Article ('. $slug .') does not exist.');
        }
    }
}