<?php
declare(strict_types=1);

namespace test\Converters\ValueModifiers;

use Src\Converters\ValueModifiers\LinkRemover;
use PHPUnit\Framework\TestCase;

class LinkRemoverTest extends TestCase
{
    public function testRemover(): void
    {
        $instance = new LinkRemover();
        $this->assertEquals(
            $instance->modify('htt p : // test .pl'),
            'htt p : // test .pl'
        );
        $this->assertEquals(
            $instance->modify('test this example.com link'),
            'test this example.com link'
        );
        $this->assertEquals(
            $instance->modify('test this www.example.com www link'),
            'test this  www link'
        );
        $this->assertEquals(
            $instance->modify('test this http://example.com link'),
            'test this  link'
        );
        $this->assertEquals(
            $instance->modify('test this https://example.com secure link'),
            'test this  secure link'
        );
        $this->assertEquals(
            $instance->modify('test this ftp://example.com ftp link'),
            'test this  ftp link'
        );
    }
}
