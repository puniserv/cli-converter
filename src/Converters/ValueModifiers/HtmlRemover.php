<?php
declare(strict_types=1);

namespace AdamDmitruczukRekrutacjaHRTec\Converters\ValueModifiers;

class HtmlRemover extends Base
{
    public function modify(string $value): string
    {
        return strip_tags($value);
    }
}
