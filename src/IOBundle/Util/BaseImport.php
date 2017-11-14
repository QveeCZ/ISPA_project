<?php

namespace IOBundle\Util;


use SchoolBundle\Entity\School;
use Symfony\Component\HttpFoundation\File\File;

abstract class BaseImport
{

    /**
     * @param File $file
     * @param School $school
     * @throws \Exception
     * @return void
     */
    public abstract function doImport($file, $school);

}