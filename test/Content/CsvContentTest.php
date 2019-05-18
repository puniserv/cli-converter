<?php
declare(strict_types=1);

namespace test\Content;

use AdamDmitruczukRekrutacjaHRTec\Content\Factory;
use AdamDmitruczukRekrutacjaHRTec\Exception\FileNotFound;
use PHPUnit\Framework\TestCase;

class CsvContentTest extends TestCase
{
    public function testCreatingFromInvalidFile(): void
    {
        $this->expectException(FileNotFound::class);
        $this->expectExceptionMessage("File with path 'invalid path' not exists");
        (new Factory())->createCsvContentFromData('invalid path');
    }
}
