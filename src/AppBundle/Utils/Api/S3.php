<?php
/**
 * Created by PhpStorm.
 * User: mark.smith
 * Date: 13/11/2017
 * Time: 13:33
 */
namespace AppBundle\Utils\Api;

use AppBundle\Utils\Api;

class S3 extends Api implements \AppBundle\Utils\Api\ApiInterface
{
    protected $storageClass = 's3';

    public function delete($what)
    {
        // TODO: Implement delete() method.
    }

    public function create($data)
    {
        $this->setData($data);

        return parent::doCreate();
    }

    public function read($what = null)
    {
        return parent::doRead();
    }

    public function update($data)
    {
        // TODO: Implement update() method.
    }
}
