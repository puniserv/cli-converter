<?php
declare(strict_types=1);

namespace AdamDmitruczukRekrutacjaHRTec\Parser;

use AdamDmitruczukRekrutacjaHRTec\Content\Content;
use AdamDmitruczukRekrutacjaHRTec\Content\JsonContent;
use AdamDmitruczukRekrutacjaHRTec\Exception\DataNotParsed;

class Json implements Parser
{
    private const JSON_ERRORS = [
        JSON_ERROR_NONE => 'No errors',
        JSON_ERROR_DEPTH => 'Maximum stack depth exceeded',
        JSON_ERROR_STATE_MISMATCH => 'Underflow or the modes mismatch',
        JSON_ERROR_CTRL_CHAR => 'Unexpected control character found',
        JSON_ERROR_SYNTAX => 'Syntax error, malformed JSON',
        JSON_ERROR_UTF8 => 'Malformed UTF-8 characters, possibly incorrectly encoded',
    ];
    /** @var \stdClass */
    private $json;
    private $errors = [];

    public function parse($stringValue): bool
    {
        $this->errors = [];
        $this->json = null;
        $json = json_decode($stringValue);
        if ($json === null) {
            $this->errors[] = $this->convertIntError(json_last_error());
            return false;
        }
        $this->json = $json;
        return true;
    }

    public function getJson(): \stdClass
    {
        if (!$this->json) {
            throw new DataNotParsed();
        }
        return $this->json;
    }

    public function getContent(): Content
    {
        return new JsonContent($this->getJson());
    }

    public function getErrors(): array
    {
        return $this->errors;
    }

    public function convertIntError(int $error): string
    {
        return self::JSON_ERRORS[$error] ?? 'Unknown error';
    }
}
