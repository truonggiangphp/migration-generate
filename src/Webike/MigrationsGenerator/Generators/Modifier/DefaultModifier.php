<?php

namespace Webike\MigrationsGenerator\Generators\Modifier;

use Doctrine\DBAL\Schema\Column;
use Webike\MigrationsGenerator\Generators\BooleanField;
use Webike\MigrationsGenerator\Generators\DatetimeField;
use Webike\MigrationsGenerator\Generators\Decorator;
use Webike\MigrationsGenerator\MigrationMethod\ColumnModifier;
use Webike\MigrationsGenerator\Types\DBALTypes;

class DefaultModifier
{
    private $booleanField;
    private $datetimeField;
    private $decorator;

    public function __construct(BooleanField $booleanField, DatetimeField $datetimeField, Decorator $decorator)
    {
        $this->booleanField = $booleanField;
        $this->datetimeField = $datetimeField;
        $this->decorator = $decorator;
    }

    /**
     * @param string $dbalType
     * @param Column $column
     * @return string
     */
    public function generate(string $dbalType, Column $column): string
    {
        switch ($dbalType) {
            case DBALTypes::SMALLINT:
            case DBALTypes::INTEGER:
            case DBALTypes::BIGINT:
            case DBALTypes::MEDIUMINT:
            case DBALTypes::TINYINT:
            case DBALTypes::DECIMAL:
            case DBALTypes::FLOAT:
            case DBALTypes::DOUBLE:
                $default = $column->getDefault();
                break;
            case DBALTypes::BOOLEAN:
                $default = $this->booleanField->makeDefault($column);
                break;
            case DBALTypes::DATETIME_MUTABLE:
            case DBALTypes::TIMESTAMP:
                return $this->datetimeField->makeDefault($column);
            default:
                $default = $this->decorator->columnDefaultToString($column->getDefault());
        }

        return $this->decorator->decorate(ColumnModifier::DEFAULT, [$default]);
    }
}
