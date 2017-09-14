<?php
/**
 * Created by PhpStorm.
 * User: mark.smith
 * Date: 23/03/2017
 * Time: 12:34
 */

namespace AppBundle\Controller;

use AppBundle\Utils\User;
use Psr\Log\LoggerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class AdminController
 *
 * @package AppBundle\Controller
 */
class AdminController extends Controller
{
    /**
     * @Route("/admin", name="admin-home")
     */
    public function indexAction(Request $request, LoggerInterface $logger)
    {
        /** @var \AppBundle\Utils\Api\Redis $api */
        $api = $this->get('app.api.redis');

        $user = new User();
        $userData = $user->isLoggedIn($request, $api, $logger);

        if (!empty($userData) && $userData['result'] && $userData['contents']->admin === true) {
            return $this->render('admin/index.html.twig', [
                'logged_in_data' => $userData,
                'logged_in_status' => $userData['result'],
                'is_admin' => $userData['contents']->admin
             ]);
        } else {
            return $this->redirectToRoute('homepage');
        }
    }

}
