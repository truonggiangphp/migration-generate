<?php

namespace Webike\MigrationsGenerator\Types;

use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\Type;
use Webike\MigrationsGenerator\MigrationMethod\ColumnType;

class MultiPolygonType extends Type
{

    /**
     * @inheritDoc
     */
    public function getSQLDeclaration(array $fieldDeclaration, AbstractPlatform $platform)
    {
        return 'MULTIPOLYGON';
    }

    /**
     * @inheritDoc
     */
    public function getName()
    {
        return ColumnType::MULTI_POLYGON;
    }
}
