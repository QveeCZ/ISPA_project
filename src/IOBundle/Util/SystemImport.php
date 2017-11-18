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
     * @return BaseImport
     */
    public function getImportClass($type){

        switch ($type){
            case "xml":
                return new XMLImport();
                break;
            case "json":
                return new JSONImport();
                break;
        }


        return null;
    }

}