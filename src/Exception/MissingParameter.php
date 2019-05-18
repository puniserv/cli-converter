<?php
declare(strict_types=1);

namespace AdamDmitruczukRekrutacjaHRTec\Exception;

class MissingParameter extends \Exception
{
    public function __construct(string $parameter)
    {
        parent::__construct(
            "Parameter '$parameter' is missing"
        );
    }
}
