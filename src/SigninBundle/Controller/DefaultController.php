<?php

namespace SigninBundle\Controller;

use Psr\Log\LoggerInterface;
use SigninBundle\Resources\GenerateCacheKey;
use AppBundle\Utils\RedisCache;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
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

                /** @var \Google_Client $client */
                $client = $google->getClient();

                $token = $client->fetchAccessTokenWithAuthCode($code);
                $client->setAccessToken($token);

                $tokenData = $client->verifyIdToken($token['id_token']);

                // Start Validation
                // @link https://developers.google.com/identity/sign-in/web/backend-auth
                if ($tokenData['aud'] !== $client->getClientId()) {
                    throw new \Exception($tokenData['aud'] . ' does not match ', $client->getClientId());
                } else {
                    if (!in_array($tokenData['iss'], [
                        'accounts.google.com',
                        'https://accounts.google.com',
                    ])
                    ) {
                        throw new \Exception($tokenData['iss'] . ' is not one of accounts.google.com, https://accounts.google.com');
                    } else {
                        if (date('U') > $tokenData['exp']) {
                            throw new \Exception('current date ' . date('U') . ' is greater than expiry: ' . $tokenData['exp']);
                        } else {
                            // Valid Request - Save to session and redirect back to this page
                            /** @var \Google_Service_Oauth2_Userinfoplus $googleUserData */
                            $googleUserData = $google->getLoggedInUser();

                            /** @var \AppBundle\Utils\Api\Redis $api */
                            $api = $this->get('app.api.redis');

                            $sessId = uniqid('sess', true);

                            /** @var LoggerInterface $logger */
                            $logger = $this->get('logger');

                            $redisCacheKey = $this->createCacheKey($request, $sessId, $logger);

                            if (!$api->read($redisCacheKey)['result']) {
                                $api->create(
                                    (array)$googleUserData->toSimpleObject() +
                                    ['admin' => $google->isSiteAdministrator($googleUserData)]
                                );
                            }
                        }
                    }
                }
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
