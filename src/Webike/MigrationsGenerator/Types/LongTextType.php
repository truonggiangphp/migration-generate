<?php

namespace Webike\MigrationsGenerator\Types;

use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\Type;
use Webike\MigrationsGenerator\MigrationMethod\ColumnType;

class LongTextType extends Type
{

    /**
     * @inheritDoc
     */
    public function getSQLDeclaration(array $fieldDeclaration, AbstractPlatform $platform)
    {
        return 'LONGTEXT';
    }

    /**
     * @inheritDoc
     */
    public function getName()
    {
        return ColumnType::LONG_TEXT;
    }
}
