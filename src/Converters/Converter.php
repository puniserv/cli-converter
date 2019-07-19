<?php
declare(strict_types=1);

namespace Src\Converters;

use Src\Content\Content;

interface Converter
{
    public function convert(Content $content): Content;
}
