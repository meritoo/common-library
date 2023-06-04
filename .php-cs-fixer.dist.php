<?php

$finder = PhpCsFixer\Finder::create()
    ->in(__DIR__)
    ->exclude([
        'build',
        'vendor',
    ])
    ->notPath([
        'tests/Resources/var/',
    ])
;

$config = new PhpCsFixer\Config();

return $config
    ->setRules([
        '@Symfony' => true,
        '@PhpCsFixer' => true,
        '@PHP81Migration' => true,
        'phpdoc_summary' => false,
    ])
    ->setFinder($finder)
;
