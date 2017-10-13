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

    /** @var  string */
    protected $adminAuthMethod;

    /** @var  string */
    protected $adminAuthValue;

    /** @var  string */
    protected $clientSecretFileName;

    public function __construct(Router $router, string $adminAuthMethod = '', string $adminAuthValue = '', string $clientSecretFileName = '')
    {

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

    public function setClientSecret($file)
    {
        $client = $this->getClient();
        $client->setAuthConfig($file);

        $this->setClient($client);
    }


    /**
     * @param \Google_Service_Oauth2_Userinfoplus $googleUserData
     *
     * @return bool
     */
    public function isSiteAdministrator(\Google_Service_Oauth2_Userinfoplus $googleUserData) :bool
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
    public function getLoggedInUser()
    {
        $googleOathV2 = new \Google_Service_Oauth2($this->getClient());

        return $googleOathV2->userinfo->get();
    }

    /**
     * Get the Client
     *
     * @return Google_Client
     */
    public function getClient()
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
    public function getLoginUrl()
    {
        return $this->getClient()->createAuthUrl();
    }

    /**
     * @return mixed
     */
    public function getAdminAuthMethod()
    {
        return $this->adminAuthMethod;
    }

    /**
     * @param mixed $adminAuthMethod
     *
     * @return Google
     */
    public function setAdminAuthMethod($adminAuthMethod)
    {
        $this->adminAuthMethod = $adminAuthMethod;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getAdminAuthValue()
    {
        return $this->adminAuthValue;
    }

    /**
     * @param mixed $adminAuthValue
     *
     * @return Google
     */
    public function setAdminAuthValue($adminAuthValue)
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


}
