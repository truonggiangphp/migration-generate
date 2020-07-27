<?php

namespace Webike\MigrationsGenerator\Generators\Modifier;

use Webike\MigrationsGenerator\MigrationMethod\ColumnType;

class NullableModifier
{
    public function shouldAddNullableModifier(string $type): bool
    {
        return !in_array($type, [ColumnType::SOFT_DELETES, ColumnType::REMEMBER_TOKEN, ColumnType::TIMESTAMPS]);
    }
}
