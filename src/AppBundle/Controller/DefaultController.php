<?php

namespace AppBundle\Controller;

use AppBundle\Utils\User;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use SigninBundle\Resources\GenerateCacheKey;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class DefaultController extends Controller
{
    use GenerateCacheKey;
    /**
     * @Route("/", name="homepage")
     */
    public function indexAction(Request $request)
    {
        $data = [];

        // replace this example code with whatever you need
        return $this->render('default/index.html.twig', $data);
    }
}
