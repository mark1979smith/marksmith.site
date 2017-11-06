<?php
/**
 * Created by PhpStorm.
 * User: mark.smith
 * Date: 20/08/2017
 * Time: 21:13
 */

namespace SigninBundle\Resources;

use Psr\Log\LoggerInterface;
use Symfony\Component\HttpFoundation\Request;

trait GenerateCacheKey
{
    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param string                                    $sessId - Cookie UUID value
     * @param \Psr\Log\LoggerInterface                  $logger
     *
     * @return string
     */
    public static function createCacheKey(Request $request, $sessId, LoggerInterface $logger)
    {
        $logger->debug('-- Creating Cache Key --');
        $httpData = array_filter($request->server->all(), function ($v, $k) {
            unset($v);

            return preg_match('/^HTTP_/i', $k)
                && !in_array($k, [
                    'HTTP_COOKIE',
                    'HTTP_PRAGMA',
                    'HTTP_CACHE_CONTROL',
                    'HTTP_REFERER',
                    'HTTP_ORIGIN',
                ]);
        }, ARRAY_FILTER_USE_BOTH);

        ksort($httpData);

        // Create cache key which is unique to this user from a mixture of cookie value and HTTP data
        $redisCacheKey = sha1($sessId . sha1(serialize($httpData)));

        $logger->debug(serialize($sessId));
        $logger->debug(serialize($httpData));
        $logger->debug(serialize($redisCacheKey));
        $logger->debug('-- End Creating Cache Key --');

        return $redisCacheKey;
    }
}
