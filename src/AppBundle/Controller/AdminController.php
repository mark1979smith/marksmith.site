<?php
/**
 * Created by PhpStorm.
 * User: mark.smith
 * Date: 23/03/2017
 * Time: 12:34
 */
namespace AppBundle\Controller;

use AppBundle\Utils\User;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
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
    public function indexAction(Request $request)
    {
        $user = new User();
        $cache = $this->get('app.redis')->get();
        if (!$user->isLoggedIn($request, $cache)) {
            return $this->render('admin/login.html.twig', [
                'core_domain' => $request->getHttpHost()
            ]);
        }

        return $this->render('admin/index.html.twig', [
        ]);
    }

    /**
     * @Route("/admin/login", name="admin_login")
     * @Method({"POST"})
     */
    public function loginAction()
    {
        /** @var App\Bundle\SigninBundle\Auth\Google $googleClient */
        $googleClient = $this->get('signin.google');

        /** @var \Google_Client $client */
        $client = $googleClient->getClient();

        /** @var string $authUrl */
        $authUrl = $client->createAuthUrl();

        return $this->redirect($authUrl);
    }
}
