<?php
declare(strict_types=1);

namespace Src\Converters\ValueModifiers;

abstract class Base
{
    abstract public function modify(string $value): string;
}
