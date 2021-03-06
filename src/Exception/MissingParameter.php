<?php
declare(strict_types=1);

namespace Src\Exception;

class MissingParameter extends \Exception
{
    public function __construct(string $parameter)
    {
        parent::__construct(
            "Parameter '$parameter' is missing"
        );
    }
}
