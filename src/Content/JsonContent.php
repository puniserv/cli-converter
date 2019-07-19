<?php
declare(strict_types=1);

namespace Src\Content;

class JsonContent extends Content
{
    public const TYPE = 'json';
    /** @var string */
    private $jsonObject;

    public function __construct(\stdClass $jsonObject)
    {
        $this->jsonObject = $jsonObject;
    }

    public function getRawStringValue(): string
    {
        return json_encode($this->jsonObject);
    }
}
