<?php
declare(strict_types=1);

namespace AdamDmitruczukRekrutacjaHRTec\Converters\ValueModifiers;

class DateTransform extends Base
{
    public function modify(string $value): string
    {
        try {
            return strftime('%d %B %Y %H:%M:%S', (new \DateTime($value))->getTimestamp());
        } catch (\Throwable $throwable) {
            return $value;
        }
    }
}