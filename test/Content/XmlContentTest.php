<?php
declare(strict_types=1);

namespace test\Content;

use AdamDmitruczukRekrutacjaHRTec\Content\Factory;
use AdamDmitruczukRekrutacjaHRTec\Exception\FileAlreadyExists;
use AdamDmitruczukRekrutacjaHRTec\Parser\Xml;
use PHPUnit\Framework\TestCase;

class XmlContentTest extends TestCase
{
    private const TEST_CSV_FILE = __DIR__ . '/../../runtime/test.csv';

    public function testSearchingInContent(): void
    {
        $parser = new Xml();
        $parser->parse('<test><item>1</item><item>2</item></test>');
        $content = $parser->getContent();
        $result = $content->getElementsByXPath('//test//item');
        $this->assertCount(2, $result);
    }

    public function testDisabledOverriding(): void
    {
        $this->clearTestFile();
        $content = (new Factory())->createEmptyXmlContent();
        $content->disableOverwrite();
        touch(self::TEST_CSV_FILE);
        $this->expectException(FileAlreadyExists::class);
        $this->expectExceptionMessage(
            "File with path '" . self::TEST_CSV_FILE . "' already exists. Maybe overwrite option is disabled?"
        );
        $content->saveToFile(self::TEST_CSV_FILE);
    }

    public function testEnabledOverriding(): void
    {
        $this->clearTestFile();
        $content = (new Factory())->createEmptyXmlContent();
        $content->enableOverwrite();
        touch(self::TEST_CSV_FILE);
        $this->assertTrue(
            $content->saveToFile(self::TEST_CSV_FILE)
        );
    }

    private function clearTestFile(): void
    {
        if (file_exists(self::TEST_CSV_FILE)) {
            unlink(self::TEST_CSV_FILE);
        }
    }
}
