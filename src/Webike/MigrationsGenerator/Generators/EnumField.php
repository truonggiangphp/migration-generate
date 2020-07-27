<?php

namespace Webike\MigrationsGenerator\Generators;

use Webike\MigrationsGenerator\Repositories\MySQLRepository;

class EnumField
{
    private $decorator;

    private $mysqlRepository;

    public function __construct(Decorator $decorator, MySQLRepository $mySQLRepository)
    {
        $this->decorator = $decorator;
        $this->mysqlRepository = $mySQLRepository;
    }

    public function makeField(string $tableName, array $field): array
    {
        $value = $this->mysqlRepository->getEnumPresetValues($tableName, $field['field']);
        if ($value !== null) {
            $field['args'][] = $value;
        }

        return $field;
    }
}
