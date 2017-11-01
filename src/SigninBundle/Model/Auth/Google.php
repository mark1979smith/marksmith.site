<?php
/**
 * Created by PhpStorm.
 * User: mark.smith
 * Date: 21/08/2017
 * Time: 11:34
 */

namespace SigninBundle\Model\Auth;

use Psr\Log\LoggerInterface;
use SigninBundle\Resources\GenerateCacheKey;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Routing\Router;
use Google_Client;

/**
 * Class Google
 *
 * @package SigninBundle\Model\Auth
 */
class Google implements AuthInterface
{
    use GenerateCacheKey;

    /**
     * @var Google_Client
     */
    protected $client;

    /** @var  string */
    protected $adminAuthMethod;

    /** @var  string */
    protected $adminAuthValue;

    /** @var  string */
    protected $clientSecretFileName;

    /** @var mixed $api */
    protected $api;

    /** @var  string */
    protected $sessId;

    /**
     * Google constructor.
     *
     * @param \Symfony\Component\Routing\Router $router
     * @param string                            $adminAuthMethod
     * @param string                            $adminAuthValue
     * @param string                            $clientSecretFileName
     */
    public function __construct(
        Router $router,
        string $adminAuthMethod,
        string $adminAuthValue,
        string $clientSecretFileName
    ) {
        $this->setClientSecretFileName(basename($clientSecretFileName))
            ->setAdminAuthMethod($adminAuthMethod)
            ->setAdminAuthValue($adminAuthValue);

        $client = new Google_Client();
        $client->setApplicationName('Marksmith.Site');

        $client->setScopes(['profile', 'email']);
        $client->setRedirectUri(
            $router->generate('signin-callback', ['originator' => 'g'], UrlGeneratorInterface::ABSOLUTE_URL)
        );

        $this->setClient($client);
    }

    /**
     * @param mixed $api
     *
     * @return \SigninBundle\Model\Auth\Google
     */
    public function setApi($api): Google
    {
        $this->api = $api;

        return $this;
    }

    public function getApi()
    {
        return $this->api;
    }

    public function validateRequest(
        Request $request,
        string $code,
        LoggerInterface $logger
    ) {

        if (!$this->getApi()) {
            throw new \Exception('API not set');
        }

        /** @var \Google_Client $client */
        $client = $this->getClient();

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
                    $googleUserData = $this->getLoggedInUser();

                    $this->sessId = uniqid('sess', true);

                    $redisCacheKey = $this->createCacheKey($request, $this->sessId, $logger);

                    if (!$this->getApi()->read($redisCacheKey)['result']) {
                        $userData = (array)$googleUserData->toSimpleObject() +
                            ['admin' => $this->isSiteAdministrator($googleUserData)];
                        $this->getApi()->create($userData);
                    }
                }
            }
        }
    }

    /**
     * The file
     *
     * @param string $file
     *
     * @throws \Exception
     */
    public function setClientSecret(string $file = '')
    {
        if (!strlen($file)) {
            throw new \Exception('Client Secret File has not been provided');
        }

        $client = $this->getClient();
        $client->setAuthConfig($file);

        $this->setClient($client);
    }


    /**
     * @param \Google_Service_Oauth2_Userinfoplus $googleUserData
     *
     * @return bool
     */
    public function isSiteAdministrator(\Google_Service_Oauth2_Userinfoplus $googleUserData): bool
    {
        if ($this->getAdminAuthMethod() == 'domain') {
            return ($googleUserData->getHd() === $this->getAdminAuthValue());
        } elseif ($this->getAdminAuthMethod() == 'verified_email') {
            return ($googleUserData->getVerifiedEmail() === $this->getAdminAuthValue());
        } elseif ($this->getAdminAuthMethod() == 'email') {
            return ($googleUserData->getEmail() === $this->getAdminAuthValue());
        }

        return false;
    }

    /**
     * Once successfully Auth'd we can get data for the user
     *
     * @return \Google_Service_Oauth2_Userinfoplus
     */
    public function getLoggedInUser(): \Google_Service_Oauth2_Userinfoplus
    {
        $googleOathV2 = new \Google_Service_Oauth2($this->getClient());

        return $googleOathV2->userinfo->get();
    }

    /**
     * Get the Client
     *
     * @return Google_Client
     */
    public function getClient(): \Google_Client
    {
        return $this->client;
    }

    /**
     * Set the Client
     *
     * @param Google_Client $client
     *
     * @return $this
     */
    public function setClient(Google_Client $client)
    {
        $this->client = $client;

        return $this;
    }

    /**
     * Get Login Url
     * This is to authorise our application to allow logins from Google
     *
     * @return string
     */
    public function getLoginUrl(): string
    {
        return $this->getClient()->createAuthUrl();
    }

    /**
     * @return string
     */
    public function getAdminAuthMethod()
    {
        return $this->adminAuthMethod;
    }

    /**
     * @param string $adminAuthMethod
     *
     * @return Google
     */
    public function setAdminAuthMethod($adminAuthMethod): Google
    {
        $this->adminAuthMethod = $adminAuthMethod;

        return $this;
    }

    /**
     * @return string
     */
    public function getAdminAuthValue(): string
    {
        return $this->adminAuthValue;
    }

    /**
     * @param string $adminAuthValue
     *
     * @return Google
     */
    public function setAdminAuthValue($adminAuthValue): Google
    {
        $this->adminAuthValue = $adminAuthValue;

        return $this;
    }

    /**
     * @return string
     */
    public function getClientSecretFileName(): string
    {
        return $this->clientSecretFileName;
    }

    /**
     * @param string $clientSecretFileName
     *
     * @return Google
     */
    public function setClientSecretFileName(string $clientSecretFileName): Google
    {
        $this->clientSecretFileName = $clientSecretFileName;

        return $this;
    }

    /**
     * @return string
     */
    public function getSessId(): string
    {
        return $this->sessId;
    }

    /**
     * @param string $sessId
     *
     * @return Google
     */
    public function setSessId(string $sessId): Google
    {
        $this->sessId = $sessId;

        return $this;
    }


}
