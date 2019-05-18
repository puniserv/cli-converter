<?php
declare(strict_types=1);

namespace test\Converters;

use AdamDmitruczukRekrutacjaHRTec\Content\CsvContent;
use AdamDmitruczukRekrutacjaHRTec\Content\Factory;
use AdamDmitruczukRekrutacjaHRTec\Content\JsonContent;
use AdamDmitruczukRekrutacjaHRTec\Content\XmlContent;
use AdamDmitruczukRekrutacjaHRTec\Converters\Modifiers\RowModifier;
use AdamDmitruczukRekrutacjaHRTec\Converters\SimpleXmlToCsv;
use AdamDmitruczukRekrutacjaHRTec\Exception\InvalidType;
use PHPUnit\Framework\TestCase;

class SimpleXmlToCsvTest extends TestCase
{
    public function testWithInvalidContent(): void
    {
        $factoryMock = $this->createMock(Factory::class);
        $instance = new SimpleXmlToCsv($factoryMock);
        $this->expectException(InvalidType::class);
        $this->expectExceptionMessage("Type 'json' is invalid. Only 'xml' is acceptable");
        $instance->convert((new Factory())->createEmptyJsonContent());
    }

    public function testConversion(): void
    {
        $factoryMock = new Factory();
        $content = new XmlContent(simplexml_load_string(<<<XML
<xml>
    <testNode>
        <test1Key>value1</test1Key>
        <test2>value2</test2>
        <test5><node>123</node></test5>
        <test>value3</test>
    </testNode>
</xml>
XML
        ));
        $instance = new SimpleXmlToCsv($factoryMock);
        $rowModifier = $this->createMock(RowModifier::class);
        $instance->modifiers = [
            $rowModifier
        ];
        $rowModifier->method(
            'modify'/** @uses RowModifier::modify() */
        )->willReturnCallback(function ($values) {
            return $values;
        });
        $rowModifier->expects($this->once())->method(
            'modify'/** @uses RowModifier::modify() */
        );
        $instance->columns = [
            'test1Key' => 'label1',
            'test2' => 'label2',
            'test5' => 'empty'
        ];
        $instance->xpathDataToAppend = '//testNode';
        $resultContent = $instance->convert($content);
        $resultString = $resultContent->getRawStringValue();
        $this->assertStringContainsString('label1,label2,empty', $resultString);
        $this->assertStringContainsString('value1,value2,', $resultString);
    }
}
