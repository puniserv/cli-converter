<?php
declare(strict_types=1);

namespace AdamDmitruczukRekrutacjaHRTec\Converters;

use AdamDmitruczukRekrutacjaHRTec\Content\Content;

interface Converter
{
    public function convert(Content $content): Content;
}
