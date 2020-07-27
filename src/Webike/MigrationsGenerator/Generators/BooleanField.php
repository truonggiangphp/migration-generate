<?php

namespace Webike\MigrationsGenerator\Generators;

use Doctrine\DBAL\Schema\Column;

class BooleanField
{
    public function makeDefault(Column $column)
    {
        return (int)$column->getDefault();
    }
}
