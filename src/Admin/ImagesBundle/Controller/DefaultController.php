<?php

namespace Admin\ImagesBundle\Controller;

use Admin\AdminControllerInterface;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\HttpFoundation\Request;

class DefaultController extends Controller implements AdminControllerInterface
{
    /**
     * @Route("/", name="image-manager")
     * @Method({"GET"})
     */
    public function indexAction(Request $request, LoggerInterface $logger)
    {
        return $this->render('ImagesBundle:Default:index.html.twig');
    }
}
