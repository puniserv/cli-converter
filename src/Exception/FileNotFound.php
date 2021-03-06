<?php
declare(strict_types=1);

namespace Src\Exception;

class FileNotFound extends \Exception
{
    public function __construct(string $path)
    {
        parent::__construct(
            "File with path '$path' not exists"
        );
    }
}
