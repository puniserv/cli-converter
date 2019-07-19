<?php
declare(strict_types=1);

namespace Src\Converters\Modifiers;

use Src\Converters\ValueModifiers\Base;

class RowModifier
{
    public $modifiers = [];

    public function modify(array $values): array
    {
        foreach($values as $key => &$value){
            $value = $this->modifyValue($key, $value);
        }
        return $values;
    }

    private function modifyValue(string $key, string $value): string
    {
        foreach($this->getModifiersByKey($key) as $modifier){
            $value = $modifier->modify($value);
        }
        return $value;
    }

    /**
     * @param string $key
     * @return Base[]
     */
    private function getModifiersByKey(string $key): array
    {
        return $this->modifiers[$key] ?? [];
    }
}
