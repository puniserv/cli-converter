<?php
declare(strict_types=1);

namespace test\Parser;

use AdamDmitruczukRekrutacjaHRTec\Exception\DataNotParsed;
use AdamDmitruczukRekrutacjaHRTec\Parser\Json;
use AdamDmitruczukRekrutacjaHRTec\Parser\Xml;
use PHPUnit\Framework\TestCase;

class JsonTest extends TestCase
{
    public function testSuccessParse(): void
    {
        $parser = new Json();
        $inputJSON = '{"test":123, "test2" : [1,2,true]}';
        $outputJSON = '{"test":123,"test2":[1,2,true]}';
        $this->assertTrue($parser->parse($inputJSON));
        $this->assertSame(
            $outputJSON,
            $parser->getContent()->getRawStringValue()
        );
        $this->assertSame(
            $outputJSON,
            json_encode($parser->getJson())
        );
        $this->assertSame(
            [],
            $parser->getErrors()
        );
    }

    public function testErrorParse(): void
    {
        $parser = new Json();
        $inputXml = '{"test":123,}';
        $this->assertFalse($parser->parse($inputXml));
        $this->expectException(DataNotParsed::class);
        $this->expectExceptionMessage('Data is not parsed yet');
        $this->assertCount(1, $parser->getErrors());
        $this->assertSame(
            "Syntax error, malformed JSON",
            $parser->getErrors()[0]
        );
        $parser->getContent();
    }
}
