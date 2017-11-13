<?php

namespace IOBundle\Util;


use Symfony\Component\HttpFoundation\File\File;

abstract class BaseImport
{

    /**
     * @param File $file
     * @return mixed
     */
    public abstract function doImport($file);

}