<?php

namespace IOBundle\Util;


use DOMDocument;
use SchoolBundle\Entity\School;
use Symfony\Component\HttpFoundation\File\File;

class XMLImport extends BaseImport
{



    /**
     * @param File $file
     * @param School $school
     * @throws \Exception
     * @return void
     */
    public function doImport($file, $school)
    {

        $xml = simplexml_load_file($file->getRealPath(), 'SimpleXmlElement', LIBXML_NOERROR+LIBXML_ERR_FATAL+LIBXML_ERR_NONE);

        if(!$this->is_valid_xml(file_get_contents($file->getRealPath()))){
            throw new \Exception("xml_file_invalid");
        }
    }


    /**
     * @param string $xml
     * @return bool
     */
    private function is_valid_xml ( $xml ) {
        libxml_use_internal_errors( true );

        $doc = new DOMDocument('1.0', 'utf-8');

        $doc->loadXML( $xml );
        $errors = libxml_get_errors();

        return empty( $errors ) && $doc->schemaValidate(__DIR__ . "/../Resources/doc/import.xsd");
    }
}