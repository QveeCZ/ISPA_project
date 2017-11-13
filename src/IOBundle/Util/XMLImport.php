<?php

namespace IOBundle\Util;


use DOMDocument;
use Symfony\Component\HttpFoundation\File\File;
use XMLReader;

class XMLImport extends BaseImport
{

    /**
     * @param File $file
     * @return mixed
     */
    public function doImport($file)
    {

        $xml = simplexml_load_file($file->getRealPath(), 'SimpleXmlElement', LIBXML_NOERROR+LIBXML_ERR_FATAL+LIBXML_ERR_NONE);

        if(!$this->is_valid_xml(file_get_contents($file->getRealPath()))){
            throw new \Exception("xml_file_invalid");
        }

    }



    private function is_valid_xml ( $xml ) {
        libxml_use_internal_errors( true );

        $doc = new DOMDocument('1.0', 'utf-8');

        $doc->loadXML( $xml );

        $errors = libxml_get_errors();

        return empty( $errors );
    }
}