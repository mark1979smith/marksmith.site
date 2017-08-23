<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use SigninBundle\Resources\GenerateCacheKey;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class DefaultController extends Controller
{
    use GenerateCacheKey;
    /**
     * @Route("/", name="homepage")
     */
    public function indexAction(Request $request)
    {
        /** @var \Symfony\Component\Cache\Adapter\RedisAdapter $cache */
        $cache = $this->get('app.redis')->get();
        $cacheToken = null;
        if ($request->cookies->has('uuid')) {
            $redisCacheKey = $this->createCacheKey($request, $request->cookies->get('uuid'));
        }

        $data = [];
        $data['base_dir'] = realpath($this->getParameter('kernel.project_dir')).DIRECTORY_SEPARATOR;
        $data['logged_in'] = (int) (isset($redisCacheKey) && $cache->hasItem($redisCacheKey));
        $data['logged_in_data'] = false;
        if ($data['logged_in']) {
            $data['logged_in_data'] = $cache->getItem($redisCacheKey)->get();
            $data['redis-cache-key'] = $redisCacheKey;
        }

        // replace this example code with whatever you need
        return $this->render('default/index.html.twig', $data);
    }
}
