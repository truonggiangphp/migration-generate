<?php

namespace Webike\MigrationsGenerator\Generators;

use Webike\MigrationsGenerator\MigrationsGeneratorSetting;
use Webike\MigrationsGenerator\MigrationMethod\PgSQLGeography;
use Webike\MigrationsGenerator\Repositories\PgSQLRepository;

class GeometryField
{
    private $pgsqlRepository;

    public function __construct(PgSQLRepository $pgSQLRepository)
    {
        $this->pgsqlRepository = $pgSQLRepository;
    }

    public function makeField(string $tableName, array $field)
    {
        /** @var MigrationsGeneratorSetting $setting */
        $setting = app(MigrationsGeneratorSetting::class);

        switch ($setting->getPlatform()) {
            case Platform::POSTGRESQL:
                $columnType = $this->pgsqlRepository->getTypeByColumnName($tableName, $field['field']);
                if ($columnType !== null) {
                    $type = strtolower($columnType);
                    $type = preg_replace('/\s+/', '', $type);

                    if (isset(PgSQLGeography::MAP[$type])) {
                        $field['type'] = PgSQLGeography::MAP[$type];
                    }
                }
                break;
            default:
        }
        return $field;
    }
}
