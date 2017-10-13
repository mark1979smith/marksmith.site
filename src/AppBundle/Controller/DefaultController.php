<?php

namespace AppBundle\Controller;

use AppBundle\Utils\User;
use Psr\Log\LoggerInterface;
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
    public function indexAction(Request $request, LoggerInterface $logger)
    {
        $data = [];
        $data['logged_in_status']  = false;

        /** @var \AppBundle\Utils\Api\Redis $api */
        $api = $this->get('app.api.redis');

        $user = new User();
        $userData = $user->isLoggedIn($request, $api, $logger);

        if (!empty($userData) && $userData['result']) {
            $data['logged_in_data'] = $userData;
            $data['logged_in_status'] = $userData['result'];
            $data['is_admin'] = $userData['contents']->admin;
        }

        // replace this example code with whatever you need
        return $this->render('default/index.html.twig', $data);
    }

    /**
     * @Route("/login", name="login")
     * @Method({"POST"})
     */
    public function loginAction()
    {
        /** @var \SigninBundle\Model\Auth\Google $googleClient */
        $googleClient = $this->get('signin.google');
        $googleClient->setClientSecret(
            $this->container->get('kernel')->locateResource(
                '@SigninBundle/Resources/keys/'. $googleClient->getClientSecretFileName()
            )
        );


        /** @var \Google_Client $client */
        $client = $googleClient->getClient();

        /** @var string $authUrl */
        $authUrl = $client->createAuthUrl();

        return $this->redirect($authUrl);
    }

    /**
     * @Route("/logout", name="logout")
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
