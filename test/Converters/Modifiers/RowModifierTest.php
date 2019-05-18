<?php
declare(strict_types=1);

namespace test\Converters\Modifiers;

use AdamDmitruczukRekrutacjaHRTec\Converters\Modifiers\RowModifier;
use AdamDmitruczukRekrutacjaHRTec\Converters\ValueModifiers\HtmlRemover;
use PHPUnit\Framework\TestCase;

class RowModifierTest extends TestCase
{
    public function testEmptyModifier(): void
    {
        $modifier = new RowModifier();
        $modifier->modifiers = [];
        $data = [
            'test' => '<div>test</div>',
            'test2' => '567',
        ];
        $this->assertSame($data, $modifier->modify($data));
    }

    public function testNotEmptyModifier(): void
    {
        $modifier = new RowModifier();
        $modifier->modifiers = [
            'test' => [
                new HtmlRemover(),
            ],
        ];
        $data = [
            'test' => '<div>test</div>',
            'test2' => '567',
        ];
        $this->assertSame([
            'test' => 'test',
            'test2' => '567',
        ], $modifier->modify($data));
    }
}
