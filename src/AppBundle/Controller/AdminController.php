<?php
/**
 * Created by PhpStorm.
 * User: mark.smith
 * Date: 23/03/2017
 * Time: 12:34
 */

namespace AppBundle\Controller;

use Admin\AdminControllerInterface;
use Psr\Log\LoggerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class AdminController
 *
 * @package AppBundle\Controller
 */
class AdminController extends Controller implements AdminControllerInterface
{
    /**
     * @Route("/admin", name="admin-home")
     * @Method({"GET"})
     *
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param \Psr\Log\LoggerInterface                  $logger
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function indexAction(Request $request, LoggerInterface $logger)
    {
        return $this->render('admin/index.html.twig');
    }

}
