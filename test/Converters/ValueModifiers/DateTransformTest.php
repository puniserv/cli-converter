<?php
declare(strict_types=1);

namespace test\Converters\ValueModifiers;

use AdamDmitruczukRekrutacjaHRTec\Converters\ValueModifiers\DateTransform;
use PHPUnit\Framework\TestCase;

class DateTransformTest extends TestCase
{
    public function testCorrectDate(): void
    {
        $this->assertEquals(
            $this->getInstance()->modify('2011/12/27 12:34:56   '),
            '27 Dec 2011 12:34:56'
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