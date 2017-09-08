<?php

namespace SigninBundle\Controller;

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
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function callback(Request $request, $originator)
    {
        if ($originator == 'g') {
            // Process Google Signin
            $code = $request->get('code');
            if ($request->get('code')) {
                /** @var \SigninBundle\Model\Auth\Google $google */
                $google = $this->get('signin.google');

                /** @var \Google_Client $client */
                $client = $google->getClient();

                $token = $client->fetchAccessTokenWithAuthCode($code);
                $client->setAccessToken($token);

                $tokenData = $client->verifyIdToken();

                // Start Validation
                // @link https://developers.google.com/identity/sign-in/web/backend-auth
                if ($tokenData['aud'] !== $client->getClientId()) {
                    throw new Exception($tokenData['aud'] . ' does not match ', $client->getClientId());
                } else {
                    if (!in_array($tokenData['iss'], [
                        'accounts.google.com',
                        'https://accounts.google.com',
                    ])
                    ) {
                        throw new Exception($tokenData['iss'] . ' is not one of accounts.google.com, https://accounts.google.com');
                    } else {
                        if (date('U') > $tokenData['exp']) {
                            throw new Exception('current date ' . date('U') . ' is greater than expiry: ' . $tokenData['exp']);
                        } else {
                            // Valid Request - Save to session and redirect back to this page
                            /** @var \Google_Service_Oauth2_Userinfoplus $googleUserData */
                            $googleUserData = $google->getLoggedInUser();

                            /** @var \AppBundle\Utils\Api\Redis $api */
                            $api = $this->get('app.api.redis');

                            $sessId = uniqid('sess', true);
                            $redisCacheKey = $this->createCacheKey($request, $sessId);

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
