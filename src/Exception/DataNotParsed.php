<?php
declare(strict_types=1);

namespace AdamDmitruczukRekrutacjaHRTec\Exception;

class DataNotParsed extends \Exception
{
    public function __construct()
    {
        parent::__construct('Data is not parsed yet');
    }
}
