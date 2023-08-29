<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;
use Rector\Set\ValueObject\LevelSetList;

return static function (RectorConfig $rectorConfig): void {
    $rectorConfig->paths([
        __DIR__ . '/src',
    ]);

    $rectorConfig->sets([
        LevelSetList::UP_TO_PHP_81,
        \Rector\Set\ValueObject\SetList::CODE_QUALITY,
        // \Rector\Set\ValueObject\SetList::CODING_STYLE,
        \Rector\Set\ValueObject\SetList::DEAD_CODE,
        \Rector\Set\ValueObject\SetList::STRICT_BOOLEANS,
        \Rector\Set\ValueObject\SetList::NAMING,
        \Rector\Set\ValueObject\SetList::TYPE_DECLARATION,
        \Rector\Set\ValueObject\SetList::EARLY_RETURN,
        \Rector\Set\ValueObject\SetList::INSTANCEOF,
    ]);
};
