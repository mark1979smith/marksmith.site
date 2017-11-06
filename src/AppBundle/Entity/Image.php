<?php
/**
 * Created by PhpStorm.
 * User: mark.smith
 * Date: 06/11/2017
 * Time: 12:18
 */

namespace AppBundle\Entity;

use Symfony\Component\Validator\Constraints as Assert;

class Image
{
    /**
     * @var string
     *
     * @Assert\NotBlank(message="Please select a file to upload")
     * @Assert\File(mimeTypes={ "image/jpeg", "image/pjpeg", "image/gif", "image/png" })
     *
     */
    private $file;

    /**
     * @return mixed
     */
    public function getFile()
    {
        return $this->file;
    }

    /**
     * @param mixed $file
     *
     * @return Image
     */
    public function setFile($file)
    {
        $this->file = $file;

        return $this;
    }


}
