<?php

namespace Webike\MigrationsGenerator\Generators\Modifier;

use Webike\MigrationsGenerator\Generators\Decorator;

class IndexModifier
{
    private $decorator;

    public function __construct(Decorator $decorator)
    {
        $this->decorator = $decorator;
    }

    public function generate(array $index): string
    {
        return $this->decorator->decorate(
            $index['type'],
            // $index['args'] is wrapped with '
            (!empty($index['args'][0]) ? [$index['args'][0]] : [])
        );
    }
}
