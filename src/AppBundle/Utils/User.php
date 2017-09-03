<?php
/**
 * Created by PhpStorm.
 * User: mark.smith
 * Date: 03/09/2017
 * Time: 14:49
 */


namespace AppBundle\Utils;

use SigninBundle\Resources\GenerateCacheKey;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Cache\Adapter\RedisAdapter;

class User
{
    use GenerateCacheKey;

    /** @var  \Symfony\Component\Cache\Adapter\RedisAdapter */
    protected $cacheAdapter;

    /** @var  string */
    protected $generatedCacheKey;

    /**
     * @param \Symfony\Component\HttpFoundation\Request     $request
     * @param \Symfony\Component\Cache\Adapter\RedisAdapter $cache
     *
     * @return bool
     */
    public function isLoggedIn(Request $request, RedisAdapter $cache): bool
    {
        $this->setCacheAdapter($cache);

        $cacheToken = null;
        if ($request->cookies->has('uuid')) {
            $this->setGeneratedCacheKey(self::createCacheKey($request, $request->cookies->get('uuid')));
        }

        return ($this->getGeneratedCacheKey() && $cache->hasItem($this->getGeneratedCacheKey()));
    }

    /**
     * @return array
     */
    public function getLoggedInData(): array
    {
        if ($this->getGeneratedCacheKey()) {
            return $this->getCacheAdapter()->getItem($this->getGeneratedCacheKey())->get();
        }

        return [];
    }

    /**
     * @return string
     */
    protected function getGeneratedCacheKey(): string
    {
        return $this->generatedCacheKey;
    }

    /**
     * @param string $generatedCacheKey
     *
     * @return User
     */
    protected function setGeneratedCacheKey($generatedCacheKey): User
    {
        $this->generatedCacheKey = $generatedCacheKey;

        return $this;
    }

    /**
     * @return \Symfony\Component\Cache\Adapter\RedisAdapter
     */
    public function getCacheAdapter(): \Symfony\Component\Cache\Adapter\RedisAdapter
    {
        return $this->cacheAdapter;
    }

    /**
     * @param \Symfony\Component\Cache\Adapter\RedisAdapter $cacheAdapter
     *
     * @return User
     */
    public function setCacheAdapter(\Symfony\Component\Cache\Adapter\RedisAdapter $cacheAdapter): User
    {
        $this->cacheAdapter = $cacheAdapter;

        return $this;
    }


}
