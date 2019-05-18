<?php
declare(strict_types=1);

namespace AdamDmitruczukRekrutacjaHRTec\Converters\ValueModifiers;

class DateTransform extends Base
{
    public function modify(string $value): string
    {
        try {
            return (new \DateTime($value))->format('d M Y H:i:s');
        } catch (\Throwable $throwable) {
            return $value;
        }
    }
}