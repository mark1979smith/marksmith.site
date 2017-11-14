<?php
/**
 * Created by PhpStorm.
 * User: mark.smith
 * Date: 04/09/2017
 * Time: 19:26
 */

namespace AppBundle\Utils;

class Api
{
    protected $storageClass;

    protected $what;

    protected $data;

    public function doRead()
    {
        $ch = curl_init();

        $options = ['storage' => $this->getStorageClass()];
        if ($this->getWhat()) {
            $options['what'] = $this->getWhat();
        }
        if ($this->getData()) {
            $options['data'] = base64_encode(serialize($this->getData()));
        }

        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');
        curl_setopt($ch, CURLOPT_URL, 'http://api/?'. http_build_query($options));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        $response = curl_exec($ch);
        curl_close($ch);

        $objResponse = \GuzzleHttp\json_decode($response);

        return (array) $objResponse;
    }

    public function doUpdate()
    {
        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, 'http://api');
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'PUT');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query(['storage' => $this->getStorageClass(), 'data' => base64_encode(serialize($this->getData()))]));

        $response = curl_exec($ch);
        curl_close($ch);

        $objResponse = \GuzzleHttp\json_decode($response);

        return (array) $objResponse;
    }

    public function doCreate()
    {
        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, 'http://api');
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query(['storage' => $this->getStorageClass(), 'what' => $this->getWhat(), 'data' => base64_encode(serialize($this->getData()))]));

        $response = curl_exec($ch);
        curl_close($ch);

        $objResponse = \GuzzleHttp\json_decode($response);

        return (array) $objResponse;
    }

    public function doDelete()
    {

    }

    /**
     * @return mixed
     */
    public function getStorageClass()
    {
        return $this->storageClass;
    }

    /**
     * @param mixed $storageClass
     *
     * @return Api
     */
    public function setStorageClass($storageClass)
    {
        $this->storageClass = $storageClass;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getWhat()
    {
        return $this->what;
    }

    /**
     * @param mixed $what
     *
     * @return Api
     */
    public function setWhat($what)
    {
        $this->what = $what;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getData()
    {
        return $this->data;
    }

    /**
     * @param mixed $data
     *
     * @return Api
     */
    public function setData($data)
    {
        $this->data = $data;

        return $this;
    }


}
