<?php
declare(strict_types=1);

namespace AdamDmitruczukRekrutacjaHRTec\Content;

use AdamDmitruczukRekrutacjaHRTec\Exception\FileAlreadyExists;

abstract class Content
{
    public const TYPE = '';
    public $overwrite = true;

    abstract public function getRawStringValue(): string;

    public function enableOverwrite(): void
    {
        $this->overwrite = true;
    }

    public function disableOverwrite(): void
    {
        $this->overwrite = false;
    }

    public function saveToFile(string $path): bool
    {
        if(!$this->overwrite && file_exists($path)){
            throw new FileAlreadyExists($path);
        }
        return file_put_contents($path, $this->getRawStringValue()) !== false;
    }
}
