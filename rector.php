<?php

declare(strict_types=1);

use Rector\CodeQuality\Rector\Class_\InlineConstructorDefaultToPropertyRector;
use Rector\CodeQuality\Rector\If_\SimplifyIfReturnBoolRector;
use Rector\CodingStyle\Rector\ArrowFunction\StaticArrowFunctionRector;
use Rector\CodingStyle\Rector\Closure\StaticClosureRector;
use Rector\Config\RectorConfig;
use Rector\Core\ValueObject\PhpVersion;
use Rector\Set\ValueObject\LevelSetList;
use Rector\Set\ValueObject\SetList;

return static function (RectorConfig $rectorConfig): void {
    $rectorConfig->phpVersion(PhpVersion::PHP_80);

    $rectorConfig->paths([
        __DIR__.'/modules',
        __DIR__.'/app',
    ]);

    $rectorConfig->indent(' ', 4);

    // $rectorConfig->fileExtensions(['php', 'phtml']); # defaults is *.php

    $rectorConfig->importNames();
    $rectorConfig->importShortClasses(false);

    // register a single rule
    $rectorConfig->rule(InlineConstructorDefaultToPropertyRector::class);

    $rectorConfig->sets([
        LevelSetList::UP_TO_PHP_81,
        SetList::DEAD_CODE,
        SetList::CODE_QUALITY,
        SetList::CODING_STYLE,
    ]);

    $rectorConfig->skip([
        __DIR__.'/modules/*/tests/*',
        // ...
        StaticArrowFunctionRector::class,
        StaticClosureRector::class,
        // SimplifyIfReturnBoolRector::class,
    ]);
};
