<?php

namespace Webike\MigrationsGenerator\Generators;

use Doctrine\DBAL\Types\Type;
use Illuminate\Support\Collection;
use Webike\MigrationsGenerator\MigrationsGeneratorSetting;
use Webike\MigrationsGenerator\Types\DoubleType;
use Webike\MigrationsGenerator\Types\EnumType;
use Webike\MigrationsGenerator\Types\GeographyType;
use Webike\MigrationsGenerator\Types\GeomCollectionType;
use Webike\MigrationsGenerator\Types\GeometryCollectionType;
use Webike\MigrationsGenerator\Types\GeometryType;
use Webike\MigrationsGenerator\Types\IpAddressType;
use Webike\MigrationsGenerator\Types\JsonbType;
use Webike\MigrationsGenerator\Types\LineStringType;
use Webike\MigrationsGenerator\Types\LongTextType;
use Webike\MigrationsGenerator\Types\MacAddressType;
use Webike\MigrationsGenerator\Types\MediumIntegerType;
use Webike\MigrationsGenerator\Types\MediumTextType;
use Webike\MigrationsGenerator\Types\MultiLineStringType;
use Webike\MigrationsGenerator\Types\MultiPointType;
use Webike\MigrationsGenerator\Types\MultiPolygonType;
use Webike\MigrationsGenerator\Types\PointType;
use Webike\MigrationsGenerator\Types\PolygonType;
use Webike\MigrationsGenerator\Types\SetType;
use Webike\MigrationsGenerator\Types\TimestampType;
use Webike\MigrationsGenerator\Types\TimestampTzType;
use Webike\MigrationsGenerator\Types\TimeTzType;
use Webike\MigrationsGenerator\Types\TinyIntegerType;
use Webike\MigrationsGenerator\Types\UUIDType;
use Webike\MigrationsGenerator\Types\YearType;

class SchemaGenerator
{
    /**
     * @var FieldGenerator
     */
    private $fieldGenerator;

    /**
     * @var ForeignKeyGenerator
     */
    private $foreignKeyGenerator;

    private $indexGenerator;

    /**
     * Custom doctrine type
     * ['class', 'name', 'type']
     * @see registerCustomDoctrineType()
     *
     * @var array
     */
    private static $customDoctrineTypes = [
        [DoubleType::class, 'double', 'double'],
        [EnumType::class, 'enum', 'enum'],
        [GeometryType::class, 'geometry', 'geometry'],
        [GeomCollectionType::class, 'geomcollection', 'geomcollection'],
        [GeometryCollectionType::class, 'geometrycollection', 'geometrycollection'],
        [LineStringType::class, 'linestring', 'linestring'],
        [LongTextType::class, 'longtext', 'longtext'],
        [MediumIntegerType::class, 'mediumint', 'mediumint'],
        [MediumTextType::class, 'mediumtext', 'mediumtext'],
        [MultiLineStringType::class, 'multilinestring', 'multilinestring'],
        [MultiPointType::class, 'multipoint', 'multipoint'],
        [MultiPolygonType::class, 'multipolygon', 'multipolygon'],
        [PointType::class, 'point', 'point'],
        [PolygonType::class, 'polygon', 'polygon'],
        [SetType::class, 'set', 'set'],
        [TimestampType::class, 'timestamp', 'timestamp'],
        [TinyIntegerType::class, 'tinyint', 'tinyint'],
        [UUIDType::class, 'uuid', 'uuid'],
        [YearType::class, 'year', 'year'],

        // Postgres types
        [GeographyType::class, 'geography', 'geography'],
        [IpAddressType::class, 'ipaddress', 'inet'],
        [JsonbType::class, 'jsonb', 'jsonb'],
        [MacAddressType::class, 'macaddress', 'macaddr'],
        [TimeTzType::class, 'timetz', 'timetz'],
        [TimestampTzType::class, 'timestamptz', 'timestamptz'],
    ];

    /**
     * @var \Doctrine\DBAL\Schema\AbstractSchemaManager
     */
    protected $schema;

    public function __construct(
        FieldGenerator $fieldGenerator,
        IndexGenerator $indexGenerator,
        ForeignKeyGenerator $foreignKeyGenerator
    )
    {
        $this->fieldGenerator = $fieldGenerator;
        $this->indexGenerator = $indexGenerator;
        $this->foreignKeyGenerator = $foreignKeyGenerator;
    }

    /**
     * @throws \Doctrine\DBAL\DBALException
     */
    public function initialize()
    {
        $setting = app(MigrationsGeneratorSetting::class);

        foreach (self::$customDoctrineTypes as $doctrineType) {
            $this->registerCustomDoctrineType(...$doctrineType);
        }

        $this->addNewDoctrineType('bit', 'boolean');
        $this->addNewDoctrineType('json', 'json');

        switch ($setting->getPlatform()) {
            case Platform::POSTGRESQL:
                $this->addNewDoctrineType('_text', 'text');
                $this->addNewDoctrineType('_int4', 'integer');
                $this->addNewDoctrineType('_numeric', 'float');
                $this->addNewDoctrineType('cidr', 'string');
                break;
            default:
        }

        $this->schema = $setting->getConnection()->getDoctrineConnection()->getSchemaManager();
    }

    /**
     * @return string[]
     */
    public function getTables(): array
    {
        return $this->schema->listTableNames();
    }

    /**
     * @param string $table
     * @return array|\Illuminate\Support\Collection[]
     * [
     *  'single' => Collection of single column indexes, with column name as key
     *  'multi' => Collection of multi columns indexes
     * ]
     */
    public function getIndexes(string $table): array
    {
        return $this->indexGenerator->generate(
            $table,
            $this->schema->listTableIndexes($table),
            app(MigrationsGeneratorSetting::class)->isIgnoreIndexNames()
        );
    }

    public function getFields(string $table, Collection $singleColIndexes): array
    {
        return $this->fieldGenerator->generate(
            $table,
            $this->schema->listTableColumns($table),
            $singleColIndexes
        );
    }

    public function getForeignKeyConstraints(string $table): array
    {
        return $this->foreignKeyGenerator->generate(
            $table,
            $this->schema->listTableForeignKeys($table),
            app(MigrationsGeneratorSetting::class)->isIgnoreForeignKeyNames()
        );
    }

    /**
     * Register custom doctrineType
     * Will override if exists
     *
     * @param string $class
     * @param string $name
     * @param string $type
     * @throws \Doctrine\DBAL\DBALException
     */
    protected function registerCustomDoctrineType(string $class, string $name, string $type): void
    {
        if (!Type::hasType($name)) {
            Type::addType($name, $class);
        } else {
            Type::overrideType($name, $class);
        }

        $this->addNewDoctrineType($type, $name);
    }

    /**
     * @param string $type
     * @param string $name
     * @throws \Doctrine\DBAL\DBALException
     */
    protected function addNewDoctrineType(string $type, string $name): void
    {
        app(MigrationsGeneratorSetting::class)->getConnection()
            ->getDoctrineConnection()
            ->getDatabasePlatform()
            ->registerDoctrineTypeMapping($type, $name);
    }
}
