<?php
declare(strict_types=1);

namespace AdamDmitruczukRekrutacjaHRTec\Content;

use AdamDmitruczukRekrutacjaHRTec\Exception\FileNotFound;

class CsvContent extends Content
{
    public const TYPE = 'csv';
    public const DELIMITER = ',';
    public const ENCLOSURE = '"';
    /*** @var string */
    private $path;
    /** @var null|resource */
    private $stream;

    public function __construct(string $path = null)
    {
        if(!file_exists($path)){
            throw new FileNotFound($path);
        }
        $this->path = $path;
    }

    public function __destruct()
    {
        $this->closeStream();
    }

    public function addRow(array $elements): bool
    {
        return fputcsv(
                $this->getStream(),
                $elements,
                self::DELIMITER,
                self::ENCLOSURE
            ) !== false;
    }

    public function getRawStringValue(): string
    {
        return (string) file_get_contents($this->path);
    }

    private function getStream()
    {
        return $this->stream ?? $this->stream = fopen($this->path, 'ab+');
    }

    private function closeStream(): void
    {
        if($this->stream){
            fclose($this->stream);
            $this->stream = null;
        }
    }
}
