<?php
declare(strict_types=1);

namespace test\Converters\ValueModifiers;

use Src\Converters\ValueModifiers\DateTransform;
use PHPUnit\Framework\TestCase;

class DateTransformTest extends TestCase
{
    public function testCorrectDate(): void
    {
        setlocale(LC_ALL, [
            'en',
            'en_GB',
        ]);
        $this->assertEquals(
            $this->getInstance()->modify('2011/12/27 12:34:56   '),
            '27 December 2011 12:34:56'
        );
    }

    public function testInvalidDate(): void
    {
        $this->assertEquals(
            $this->getInstance()->modify('some invalid date'),
            'some invalid date'
        );
    }

    private function getInstance(): DateTransform
    {
        return new DateTransform();
    }
}
