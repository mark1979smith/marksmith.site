<?php
/**
 * Created by PhpStorm.
 * User: mark.smith
 * Date: 23/09/2017
 * Time: 19:31
 */

namespace AppBundle\Utils\Api;

use AppBundle\Utils\Api;

class Mysql extends Api implements ApiInterface
{
    protected $storageClass = 'mysql';

    protected $logger;

    public function __construct()
    {
        $this->setStorageClass($this->storageClass);
    }

    public function read($what, $data = null)
    {
        $this->setWhat($what);
        if (!is_null($data)) {
            $this->setData($data);
        }

        return parent::doRead();
    }

    public function update($data)
    {
        $this->setData($data);

        return parent::doUpdate();
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
