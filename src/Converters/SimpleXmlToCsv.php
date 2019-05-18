<?php
declare(strict_types=1);

namespace AdamDmitruczukRekrutacjaHRTec\Converters;

use AdamDmitruczukRekrutacjaHRTec\Content\Content;
use AdamDmitruczukRekrutacjaHRTec\Content\CsvContent;
use AdamDmitruczukRekrutacjaHRTec\Content\Factory;
use AdamDmitruczukRekrutacjaHRTec\Converters\Modifiers\RowModifier;
use AdamDmitruczukRekrutacjaHRTec\Exception\InvalidType;
use AdamDmitruczukRekrutacjaHRTec\Content\XmlContent;

class SimpleXmlToCsv implements Converter
{
    public $columns = [];
    public $xpathDataToAppend = '//';
    /** @return RowModifier[] */
    public $modifiers = [];
    /*** @var Factory */
    private $contentFactory;

    public function __construct(Factory $contentFactory)
    {
        $this->contentFactory = $contentFactory;
    }

    public function convert(Content $content): Content
    {
        if (!$content instanceof XmlContent) {
            throw new InvalidType($content::TYPE, XmlContent::TYPE);
        }
        $csvContent = $this->contentFactory->createEmptyCsvContent();
        $this->addTitleRow($csvContent);
        $this->addData($csvContent, $content);
        return $csvContent;
    }

    protected function getDataToAppend(XmlContent $xmlContent): iterable
    {
        return $xmlContent->getElementsByXPath($this->xpathDataToAppend);
    }

    protected function addTitleRow(CsvContent $csvContent): void
    {
        $rowData = [];
        foreach ($this->columns as $column){
            $rowData[] = $column;
        }
        $csvContent->addRow($rowData);
    }

    /**
     * @return RowModifier[]
     */
    protected function getModifiers(): array
    {
        return $this->modifiers;
    }

    private function addData(CsvContent $csvContent, XmlContent $xmlContent): void
    {
        foreach ($this->getDataToAppend($xmlContent) as $item) {
            $rowData = [];
            $values = (array)$item;
            foreach (array_keys($this->columns) as $columnKey){
                $value = (string)($values[$columnKey] ?? null);
                $rowData[$columnKey] = $value;
            }
            $csvContent->addRow($this->transformValues($rowData));
        }
    }

    private function transformValues(array $rowData): array
    {
        foreach($this->getModifiers() as $rowModifier){
            $rowData = $rowModifier->modify($rowData);
        }
        return $rowData;
    }
}
