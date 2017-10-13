<?php
/**
 * Created by PhpStorm.
 * User: mark.smith
 * Date: 21/08/2017
 * Time: 11:33
 */

namespace SigninBundle\Model\Auth;


use Psr\Log\LoggerInterface;
use Symfony\Component\HttpFoundation\Request;

interface AuthInterface
{
    public function getLoggedInUser();

    public function getLoginUrl();

    public function setApi($api);

    public function getApi();

    public function setSessId(string $sessionId);

    public function getSessId();

    public static function createCacheKey(Request $request, $sessId, LoggerInterface $logger);

    public function validateRequest(
        Request $request,
        string $code,
        LoggerInterface $logger
    );
}
