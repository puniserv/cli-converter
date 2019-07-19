<?php
declare(strict_types=1);

namespace Src\Converters\ValueModifiers;

class LinkRemover extends Base
{
    public function modify(string $value): string
    {
        return preg_replace(
            '/\b((https?|ftp|file):\/\/|www\.)[-A-Z0-9+&@#\/%?=~_|$!:,.;]*[A-Z0-9+&@#\/%=~_|$]/i',
            '',
            $value
        );
    }
}
