<?php
declare(strict_types=1);

namespace Src\Parser;

interface Parser
{
    public function parse($stringValue): bool;
    public function getErrors(): array;
    public function getContent();
}
