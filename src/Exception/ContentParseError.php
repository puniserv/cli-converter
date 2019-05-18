<?php
declare(strict_types=1);

namespace AdamDmitruczukRekrutacjaHRTec\Exception;

class ContentParseError extends \Exception
{
    public function __construct(array $errors)
    {
        parent::__construct(sprintf(
            'During the parsing, the following errors appeared: %s',
            $this->prepareStringErrors($errors)
        ));
    }

    protected function prepareStringErrors(array $errors): string
    {
        return implode(', ', array_map(function (\LibXMLError $error) {
            return $error->message;
        }, $errors));
    }
}
