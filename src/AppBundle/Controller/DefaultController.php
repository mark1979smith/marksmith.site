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
        /** @var \AppBundle\Utils\Api\Redis $cache */
        $cache = $this->get('app.api.redis');

        $user = new User();
        $data = [];
        $data['base_dir'] = realpath($this->getParameter('kernel.project_dir')).DIRECTORY_SEPARATOR;
        $data['logged_in'] = (int) $user->isLoggedIn($request, $cache);
        $data['logged_in_data'] = false;
        if ($data['logged_in']) {
            $data['logged_in_data'] = $user->getLoggedInData();
        }

        // replace this example code with whatever you need
        return $this->render('default/index.html.twig', $data);
    }
}
