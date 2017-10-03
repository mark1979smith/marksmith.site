<?php
/**
 * Created by PhpStorm.
 * User: mark.smith
 * Date: 04/09/2017
 * Time: 19:33
 */

namespace AppBundle\Utils\Api;

interface ApiInterface
{
    /**
     * @param string $what
     *
     * @return boolean|array
     */
    public function read($what);

    /**
     * @param object $data
     *
     * @return mixed
     */
    public function update($data);

    /**
     * @param array|object $data
     *
     * @return mixed
     */
    public function create($data);

    /**
     * @param string $what
     *
     * @return mixed
     */
    public function delete($what);
}
