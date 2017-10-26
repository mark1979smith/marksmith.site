<?php

namespace Admin\ImagesBundle\Controller;

use Admin\AdminController;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;

class DefaultController extends Controller implements AdminController
{
    /**
     * @Route("/", name="image-manager")
     */
    public function indexAction(Request $request, LoggerInterface $logger)
    {
        return $this->render('ImagesBundle:Default:index.html.twig');
    }
}
