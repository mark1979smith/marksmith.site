<?php

namespace Admin\PagesBundle\Controller;

use AppBundle\Entity\Article;
use AppBundle\Entity\ArticleHistory;
use AppBundle\Utils\User;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Request;

class DefaultController extends Controller
{
    /**
     * @Route("", name="page-manager")
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param \Psr\Log\LoggerInterface                  $logger
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function pagesListAction(Request $request, LoggerInterface $logger)
    {
        /** @var \AppBundle\Utils\Api\Redis $redisApi */
        $redisApi = $this->get('app.api.redis');

        /** @var \AppBundle\Utils\Api\Mysql $api */
        $mysqlApi = $this->get('app.api.mysql');


        $user = new User();
        $userData = $user->isLoggedIn($request, $redisApi, $logger);

        if (!empty($userData) && $userData['result'] && $userData['contents']->admin === true) {
            $status = $mysqlApi->read(Article::class);
            $results = unserialize(base64_decode($status['results']));

            return $this->render('PagesBundle:Default:index.html.twig', [
                'logged_in_data' => $userData,
                'logged_in_status' => $userData['result'],
                'is_admin' => $userData['contents']->admin,
                'results' => $results
            ]);
        } else {
            return $this->redirectToRoute('homepage');
        }
    }

    /**
     * @Route("/create", name="page-manager-create")
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param \Psr\Log\LoggerInterface                  $logger
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function pagesCreateAction(Request $request, LoggerInterface $logger)
    {
        /** @var \AppBundle\Utils\Api\Redis $redisApi */
        $redisApi = $this->get('app.api.redis');

        /** @var \AppBundle\Utils\Api\Mysql $api */
        $mysqlApi = $this->get('app.api.mysql');

        $user = new User();
        $userData = $user->isLoggedIn($request, $redisApi, $logger);

        $article = new Article();

        $form = $this->createFormBuilder($article)
            ->add('articleName', TextType::class)
            ->add('articleSlug', TextType::class)
            ->add('articleTeaser', TextareaType::class)
            ->add('articleBody', TextareaType::class)
            ->add('save', SubmitType::class, ['label' => 'Create Article'])
            ->getForm();

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            /** @var \AppBundle\Entity\Article $article */
            $article = $form->getData();
            $this->addFlash(
                'success',
                'Your page has been created.'
            );
            $mysqlApi->create($article);

            return $this->redirectToRoute('page-manager');
        }

        if (!empty($userData) && $userData['result'] && $userData['contents']->admin === true) {
            return $this->render('PagesBundle:Default:create.html.twig', [
                'logged_in_data' => $userData,
                'logged_in_status' => $userData['result'],
                'is_admin' => $userData['contents']->admin,
                'form' => $form->createView()
            ]);
        } else {
            return $this->redirectToRoute('homepage');
        }
    }

    /**
     * @Route("/edit/{id}", name="page-manager-edit")
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param \Psr\Log\LoggerInterface                  $logger
     * @param int $id
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function pagesEditAction(Request $request, LoggerInterface $logger, $id)
    {
        /** @var \AppBundle\Utils\Api\Redis $redisApi */
        $redisApi = $this->get('app.api.redis');

        /** @var \AppBundle\Utils\Api\Mysql $mysqlApi */
        $mysqlApi = $this->get('app.api.mysql');

        $user = new User();
        $userData = $user->isLoggedIn($request, $redisApi, $logger);

        $article = new Article();
        $article->setId($id);
        $status = $mysqlApi->read(Article::class, $article);
        $article = unserialize(base64_decode($status['results']))[0];
        $existingArticle = clone $article;

        $form = $this->createFormBuilder($article)
            ->add('articleName', TextType::class)
            ->add('articleSlug', TextType::class)
            ->add('articleTeaser', TextareaType::class)
            ->add('articleBody', TextareaType::class)
            ->add('save', SubmitType::class, ['label' => 'Save Article'])
            ->getForm();

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            /** @var \AppBundle\Entity\Article $article */
            $article = $form->getData();

            $status = $mysqlApi->update($article);

            if ($status) {
                // Create Revision
                $articleHistory = new ArticleHistory($existingArticle);
                $articleHistory->setArticleId($id);
                $mysqlApi->create($articleHistory);
            }
            $this->addFlash(
                'success',
                'Your page has been edited.'
            );

            return $this->redirectToRoute('page-manager');
        }

        if (!empty($userData) && $userData['result'] && $userData['contents']->admin === true) {
            return $this->render('PagesBundle:Default:create.html.twig', [
                'logged_in_data' => $userData,
                'logged_in_status' => $userData['result'],
                'is_admin' => $userData['contents']->admin,
                'form' => $form->createView()
            ]);
        } else {
            return $this->redirectToRoute('homepage');
        }
    }

}
