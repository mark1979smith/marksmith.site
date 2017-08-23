<?php
/**
 * Created by PhpStorm.
 * User: mark.smith
 * Date: 20/08/2017
 * Time: 21:13
 */
namespace SigninBundle\Resources;

use Symfony\Component\HttpFoundation\Request;

trait GenerateCacheKey
{
    public function createCacheKey(Request $request, $sessId)
    {

        $httpData = array_filter($_SERVER, function ($v, $k) {
            unset($v);

            return preg_match('/^HTTP_/i', $k)
                && !in_array($k, [
                    'HTTP_COOKIE', 'HTTP_PRAGMA', 'HTTP_CACHE_CONTROL'
                ]);
        }, ARRAY_FILTER_USE_BOTH);
        $redisCacheKey = sha1($sessId . sha1(serialize($httpData)));

        return $redisCacheKey;
    }
}
