<?php
/**
 * Created by PhpStorm.
 * User: mark.smith
 * Date: 21/08/2017
 * Time: 11:33
 */

namespace SigninBundle\Model\Auth;


interface AuthInterface
{
    public function getLoggedInUser();

    public function getLoginUrl();
}
