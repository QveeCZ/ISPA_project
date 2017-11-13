<?php

namespace IOBundle\Util;


abstract class BaseImport
{

    /**
     * @param $file
     * @return mixed
     */
    public abstract function doImport($file);

}