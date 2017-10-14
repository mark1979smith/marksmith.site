<?php

namespace AppBundle\Controller;

use SigninBundle\Resources\GenerateCacheKey;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Response;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;

class UserController extends Controller
{
    use GenerateCacheKey;

    /**
     * @Route("/user/login", name="login")
     * @Method({"POST"})
     */
    public function loginAction()
    {
        /** @var \SigninBundle\Model\Auth\Google $googleClient */
        $googleClient = $this->get('signin.google');
        $googleClient->setClientSecret(
            $this->container->get('kernel')->locateResource(
                '@SigninBundle/Resources/keys/' . $googleClient->getClientSecretFileName()
            )
        );


        /** @var \Google_Client $client */
        $client = $googleClient->getClient();

        /** @var string $authUrl */
        $authUrl = $client->createAuthUrl();

        return $this->redirect($authUrl);
    }

    /**
     * @Route("/user/logout", name="logout")
     * @Method({"GET"})
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
