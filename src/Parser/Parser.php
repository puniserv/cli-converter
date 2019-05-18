<?php
declare(strict_types=1);

namespace AdamDmitruczukRekrutacjaHRTec\Parser;

interface Parser
{
    public function parse($stringValue): bool;
    public function getErrors(): array;
    public function getContent();
}
