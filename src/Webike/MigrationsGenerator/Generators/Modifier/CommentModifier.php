<?php

namespace Webike\MigrationsGenerator\Generators\Modifier;

use Webike\MigrationsGenerator\Generators\Decorator;
use Webike\MigrationsGenerator\MigrationMethod\ColumnModifier;

class CommentModifier
{
    private $decorator;

    public function __construct(Decorator $decorator)
    {
        $this->decorator = $decorator;
    }

    public function generate(string $comment): string
    {
        return $this->decorator->decorate(
            ColumnModifier::COMMENT,
            ["'" . $this->decorator->addSlash($comment) . "'"]
        );
    }
}
