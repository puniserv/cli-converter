<?php
declare(strict_types=1);

namespace AdamDmitruczukRekrutacjaHRTec\Parser;

use AdamDmitruczukRekrutacjaHRTec\Content\Content;
use AdamDmitruczukRekrutacjaHRTec\Content\XmlContent;
use AdamDmitruczukRekrutacjaHRTec\Exception\DataNotParsed;

class Xml implements Parser
{
    /** @var \SimpleXMLElement */
    private $xml;
    /** @var array */
    private $errors = [];

    public function parse($stringValue): bool
    {
        $this->errors = [];
        $this->xml = null;
        libxml_use_internal_errors(true);
        $xml = simplexml_load_string($stringValue);
        if($xml === false){
            $this->errors = libxml_get_errors();
            libxml_clear_errors();
            return false;
        }
        $this->xml = $xml;
        return true;
    }

    public function getXml(): \SimpleXMLElement
    {
        if(!$this->xml){
            throw new DataNotParsed();
        }
        return $this->xml;
    }

    public function getContent(): XmlContent
    {
        return new XmlContent($this->getXml());
    }

    public function getErrors():array
    {
        return $this->errors;
    }
}