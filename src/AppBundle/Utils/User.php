<?php
/**
 * Created by PhpStorm.
 * User: mark.smith
 * Date: 03/09/2017
 * Time: 14:49
 */


namespace AppBundle\Utils;

use AppBundle\Utils\Api\Redis;
use SigninBundle\Resources\GenerateCacheKey;
use Symfony\Component\HttpFoundation\Request;

class User
{
    use GenerateCacheKey;

    /** @var  \AppBundle\Utils\Api\Redis */
    protected $cacheAdapter;

    /** @var  string */
    protected $generatedCacheKey;

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param \AppBundle\Utils\Api\Redis                $api
     *
     * @return bool
     */
    public function isLoggedIn(Request $request, Redis $api): bool
    {
        $this->setCacheAdapter($api);

        $cacheToken = null;
        if ($request->cookies->has('uuid')) {
            $this->setGeneratedCacheKey(self::createCacheKey($request, $request->cookies->get('uuid')));
        }

        return ($this->getGeneratedCacheKey() && $api->read($this->getGeneratedCacheKey())['result']);
    }

    /**
     * @return array
     */
    public function getLoggedInData(): array
    {
        if ($this->getGeneratedCacheKey()) {
            return $this->getCacheAdapter()->read($this->getGeneratedCacheKey());
        }

        return [];
    }

    /**
     * @return string
     */
    protected function getGeneratedCacheKey(): string
    {
        return $this->generatedCacheKey ?: '';
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
     * @return \AppBundle\Utils\Api\Redis
     */
    public function getCacheAdapter(): \AppBundle\Utils\Api\Redis
    {
        return $this->cacheAdapter;
    }

    /**
     * @param \AppBundle\Utils\Api\Redis $cacheAdapter
     *
     * @return User
     */
    public function setCacheAdapter(\AppBundle\Utils\Api\Redis $cacheAdapter): User
    {
        $this->cacheAdapter = $cacheAdapter;

        return $this;
    }


}
