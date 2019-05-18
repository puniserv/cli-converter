<?php
declare(strict_types=1);

namespace AdamDmitruczukRekrutacjaHRTec\Content;

class Factory
{
    public function createEmptyCsvContent(): CsvContent
    {
        return $this->createCsvContentFromData($this->createTempFile());
    }

    public function createCsvContentFromData(string $data): CsvContent
    {
        return new CsvContent($data);
    }

    public function createEmptyJsonContent(): JsonContent
    {
        return $this->createJsonContentFromData((object)[]);
    }

    public function createJsonContentFromData(\stdClass $object): JsonContent
    {
        return new JsonContent($object);
    }

    public function createEmptyXmlContent(): XmlContent
    {
        return $this->createXmlContentFromData(simplexml_load_string('<xml></xml>'));
    }

    public function createXmlContentFromData(\SimpleXMLElement $data): XmlContent
    {
        return new XmlContent($data);
    }

    private function createTempFile(): string
    {
        return tempnam(__DIR__ . '/../../runtime/', 'content');
    }
}
