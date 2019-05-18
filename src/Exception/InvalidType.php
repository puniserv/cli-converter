<?php
declare(strict_types=1);

namespace AdamDmitruczukRekrutacjaHRTec\Exception;

class InvalidType extends \Exception
{
    public function __construct(string $actualType, string $supportedType)
    {
        parent::__construct(
            "Type '$actualType' is invalid. Only '$supportedType' is acceptable"
        );
    }
}
