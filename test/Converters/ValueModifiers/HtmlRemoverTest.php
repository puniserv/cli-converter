<?php
declare(strict_types=1);

namespace test\Converters\ValueModifiers;

use Src\Converters\ValueModifiers\HtmlRemover;
use PHPUnit\Framework\TestCase;

final class HtmlRemoverTest extends TestCase
{
    public function testHtmlRemover(): void
    {
        $instance = new HtmlRemover();
        $this->assertEquals(
            $instance->modify('test<br> test'),
            'test test'
        );
        $this->assertEquals(
            $instance->modify('<div>testy<i> ico</i></div>'),
            'testy ico'
        );
        $this->assertEquals(
            $instance->modify('żółć'),
            'żółć'
        );
    }

    public function modify(string $value): string
    {
        return strip_tags($value);
    }
}
