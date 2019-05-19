<?php
declare(strict_types=1);

namespace AdamDmitruczukRekrutacjaHRTec\Exception;

class WarningException extends \ErrorException
{
    public function __construct($message, $int, $error, $file, $line)
    {
        parent::__construct($message, $int, $error, $file, $line);
    }
}
