<?php
/**
 * Created by PhpStorm.
 * User: mark.smith
 * Date: 21/08/2017
 * Time: 11:34
 */

namespace SigninBundle\Model\Auth;


use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Routing\Router;
use Google_Client;

class Google implements AuthInterface
{
    /**
     * @var Google_Client
     */
    protected $client;

    protected $clientId;


    public function __construct(Router $router)
    {
        $client = new Google_Client();
        $client->setApplicationName('Marksmith.Site');

        $client->setAuthConfig(realpath(__DIR__ . DIRECTORY_SEPARATOR . implode(DIRECTORY_SEPARATOR, [
                '..', '..', 'Resources', 'keys', 'client_secret.json'
            ])));
        $client->setScopes(['profile', 'email']);
        $client->setRedirectUri($router->generate('signin-callback', ['originator' => 'g'], UrlGeneratorInterface::ABSOLUTE_URL));

        $this->setClient($client);
    }


    /**
     * Once successfully Auth'd we can get data for the user
     * @return \Google_Service_Oauth2_Userinfoplus
     */
    public function getLoggedInUser()
    {
        $googleOathV2 = new \Google_Service_Oauth2($this->getClient());

        return $googleOathV2->userinfo->get();
    }

    /**
     * Get the Client
     * @return Google_Client
     */
    public function getClient()
    {
        return $this->client;
    }

    /**
     * Set the Client
     * @param Google_Client $client
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
     * @return string
     */
    public function getLoginUrl()
    {
        return $this->getClient()->createAuthUrl();
    }
}
