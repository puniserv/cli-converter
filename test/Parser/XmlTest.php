<?php
declare(strict_types=1);

namespace test\Parser;

use Src\Exception\DataNotParsed;
use Src\Parser\Xml;
use PHPUnit\Framework\TestCase;

class XmlTest extends TestCase
{
    public function testSuccessParse(): void
    {
        $parser = new Xml();
        $inputXml = '<test>123</test>';
        $outputXml = "<?xml version=\"1.0\"?>\n<test>123</test>\n";
        $this->assertTrue($parser->parse($inputXml));
        $this->assertSame(
            $outputXml,
            $parser->getContent()->getRawStringValue()
        );
        $this->assertSame(
            $outputXml,
            $parser->getXml()->asXML()
        );
        $this->assertSame(
            [],
            $parser->getErrors()
        );
    }

    public function testErrorParse(): void
    {
        $parser = new Xml();
        $inputXml = '<test>123';
        $this->assertFalse($parser->parse($inputXml));
        $this->expectException(DataNotParsed::class);
        $this->expectExceptionMessage('Data is not parsed yet');
        $this->assertCount(1, $parser->getErrors());
        $this->assertSame(
            "Premature end of data in tag test line 1\n",
            $parser->getErrors()[0]->message
        );
        $parser->getContent();
    }
}
