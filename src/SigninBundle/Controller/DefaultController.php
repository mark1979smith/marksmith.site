<?php

namespace SigninBundle\Controller;

use SigninBundle\Resources\GenerateCacheKey;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\HttpFoundation\Cookie;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class DefaultController extends Controller
{
    use GenerateCacheKey;

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param string                                    $originator
     *
     * @Route("/callback/{originator}", name="signin-callback", requirements={"originator": "[g]{1}"})
     * @Method({"GET"})
     *
     * @throws \Exception
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function callbackAction(Request $request, $originator)
    {
        if ($originator == 'g') {
            // Process Google Signin
            $code = $request->get('code');
            if ($code) {
                /** @var \SigninBundle\Model\Auth\Google $google */
                $google = $this->get('signin.google');
                $google->setClientSecret(
                    $this->container->get('kernel')->locateResource(
                        '@SigninBundle/Resources/keys/'. $google->getClientSecretFileName()
                    )
                );

                $google->setApi($this->get('app.api.redis'))
                    ->validateRequest($request, $code, $this->get('logger'));

                $sessId = $google->getSessId();
            }
        }

        $response = new Response(
            '',
            Response::HTTP_OK,
            array('content-type' => 'text/html')
        );
        $response->headers->setCookie(new Cookie('uuid', $sessId));
        $response->sendHeaders();

        return $this->redirectToRoute('homepage');
    }
}
