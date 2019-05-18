<?php
declare(strict_types=1);

namespace AdamDmitruczukRekrutacjaHRTec\Converters\ValueModifiers;

abstract class Base
{
    abstract public function modify(string $value): string;
}
