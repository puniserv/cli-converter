<?php
declare(strict_types=1);

namespace Src\Common;
/**
 * @codeCoverageIgnore
 */
class Manager
{
    public function endWithSuccess(): void
    {
        exit(0);
    }

    public function endWithError(): void
    {
        exit(1);
    }
}
