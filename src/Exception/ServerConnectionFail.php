<?php
declare(strict_types=1);

namespace AdamDmitruczukRekrutacjaHRTec\Exception;

class ServerConnectionFail extends \Exception
{
    public function __construct(string $message, int $code)
    {
        parent::__construct(sprintf(
            'Server connection fail. Code: "%s", Message: "%s"',
            $code,
            $message
        ));
    }
}
