<?php
declare(strict_types=1);

namespace AdamDmitruczukRekrutacjaHRTec\Content;

use SimpleXMLElement;

class XmlContent extends Content
{
    public const TYPE = 'xml';
    /** @var \SimpleXMLElement */
    private $element;

    public function __construct(\SimpleXMLElement $element)
    {
        $this->element = $element;
    }

    /**
     * @param string $xpath
     * @return SimpleXMLElement[]
     */
    public function getElementsByXPath(string $xpath): array
    {
        return (array)$this->element->xpath($xpath);
    }

    public function getRawStringValue(): string
    {
        return (string)$this->element->asXML();
    }
}
