<?php
/**
 * Created by PhpStorm.
 * User: mark.smith
 * Date: 23/03/2017
 * Time: 12:34
 */

namespace AppBundle\Controller;

use Admin\AdminControllerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

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
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function indexAction()
    {
        return $this->render('admin/index.html.twig');
    }

}
