<?php

namespace IOBundle\Util;

use Doctrine\ORM\EntityManager;

class SystemImport
{

    /**
     * @var EntityManager $em
     */
    protected $em;

    /**
     * SystemExport constructor.
     * @param EntityManager $em
     */
    public function __construct($em)
    {
        $this->em = $em;
    }

    /**
     * @param string $type
     */
    public function getImportClass($type){

        echo $type;
    }

}