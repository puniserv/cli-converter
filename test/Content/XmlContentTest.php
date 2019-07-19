<?php
declare(strict_types=1);

namespace test\Content;

use Src\Common\App;
use Src\Common\Manager;
use Src\Content\Factory;
use Src\Exception\FileAlreadyExists;
use Src\Exception\FileIsNotWritable;
use Src\Exception\FileNotFound;
use Src\Parser\Xml;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Console\Application;

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
        $fileContent = file_get_contents(self::TEST_CSV_FILE);
        $content->saveToFile(self::TEST_CSV_FILE);
        $this->assertNotSame(
            $fileContent,
            file_get_contents(self::TEST_CSV_FILE)
        );
    }

    public function testSavingWithError(): void
    {
        $app = new App($this->createMock(Application::class), $this->createMock(Manager::class));
        $app->run();
        $this->clearTestFile();
        touch(self::TEST_CSV_FILE);
        $file = fopen(self::TEST_CSV_FILE, 'ab');
        flock($file, LOCK_EX);
        $this->expectException(FileIsNotWritable::class);
        $this->expectExceptionMessage("File with path '" . self::TEST_CSV_FILE . "' is not writable. " .
            'Check if you have write access to this file or if another program does not use this file');
        $content = (new Factory())->createEmptyXmlContent();
        $content->enableOverwrite();
        $content->saveToFile(self::TEST_CSV_FILE);
    }

    private function clearTestFile(): void
    {
        if (file_exists(self::TEST_CSV_FILE)) {
            unlink(self::TEST_CSV_FILE);
        }
    }
}
