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
                return new XMLImport($this->em);
                break;
            case "json":
                return new JSONImport($this->em);
                break;
            case "csv":
                return new CSVImport($this->em);
                break;
        }


        return null;
    }

}