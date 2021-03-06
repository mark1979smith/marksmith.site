<?php
/**
 * Created by PhpStorm.
 * User: mark.smith
 * Date: 04/09/2017
 * Time: 19:31
 */

namespace AppBundle\Utils\Api;

use AppBundle\Utils\Api;

class Redis extends Api implements \AppBundle\Utils\Api\ApiInterface
{
    protected $storageClass = 'redis';

    protected $logger;

    public function __construct()
    {
        $this->setStorageClass($this->storageClass);
    }

    public function read($what)
    {
        $this->setWhat($what);

        return parent::doRead();
    }

    public function update($data)
    {

    }

    public function create($data)
    {
        $this->setData($data);

        return parent::doCreate();
    }

    public function delete($what)
    {

    }
}
