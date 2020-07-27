<?php

namespace Webike\MigrationsGenerator\Generators;

class OtherField
{
    public function makeField(array $field): array
    {
        if (isset(FieldGenerator::$fieldTypeMap[$field['type']])) {
            $field['type'] = FieldGenerator::$fieldTypeMap[$field['type']];
        }
        return $field;
    }
}
