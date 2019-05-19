<?php
declare(strict_types=1);

namespace AdamDmitruczukRekrutacjaHRTec\Exception;

class FileIsNotWritable extends \Exception
{
    public function __construct(string $path)
    {
        parent::__construct(
            "File with path '$path' is not writable. " .
            'Check if you have write access to this file or if another program does not use this file'
        );
    }
}
