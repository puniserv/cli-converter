<?php
declare(strict_types=1);

namespace Src\Exception;

class FileAlreadyExists extends \Exception
{
    public function __construct(string $path)
    {
        parent::__construct(
            "File with path '$path' already exists. Maybe overwrite option is disabled?"
        );
    }
}
