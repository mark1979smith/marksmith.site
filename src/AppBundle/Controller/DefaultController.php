<?php

namespace AppBundle\Controller;

use AppBundle\Utils\User;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use SigninBundle\Resources\GenerateCacheKey;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\HttpFoundation\Response;

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

    /**
     * @Route("/login", name="login")
     * @Method({"POST"})
     */
    public function loginAction()
    {
        /** @var App\Bundle\SigninBundle\Auth\Google $googleClient */
        $googleClient = $this->get('signin.google');

        /** @var \Google_Client $client */
        $client = $googleClient->getClient();

        /** @var string $authUrl */
        $authUrl = $client->createAuthUrl();

        return $this->redirect($authUrl);
    }

    /**
     * @Route("/Logout", name="logout")
     * @Method({"GET"})
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function logoutAction()
    {
        $response = new Response(
            '',
            Response::HTTP_OK,
            array('content-type' => 'text/html')
        );
        $response->headers->clearCookie('uuid');
        $response->sendHeaders();

        return $this->redirectToRoute('homepage');
    }
}
