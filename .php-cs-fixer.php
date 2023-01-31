<?php
declare(strict_types=1);

$fixers = [
    '@Symfony' => true,
    'array_syntax' => [
        'syntax' => 'short',
    ],
    'combine_consecutive_unsets' => true,
    'no_useless_else' => true,
    'no_useless_return' => true,
    'ordered_imports' => [
        'sort_algorithm' => 'length',
    ],
    'phpdoc_indent' => false,
    'phpdoc_annotation_without_dot' => false,
    'phpdoc_no_empty_return' => false,
    'phpdoc_no_alias_tag' => [
        'replacements' => [
            // 'property-read' => 'property',
            // 'property-write' => 'property',
            'type' => 'var',
            'link' => 'see',
        ],
    ],
    'no_superfluous_phpdoc_tags' => false,
    'concat_space' => [
        'spacing' => 'one',
    ],
    'yoda_style' => false,
];

return (new \PhpCsFixer\Config())
    ->setFinder(
        PhpCsFixer\Finder::create()
            ->in(__DIR__ . '/src')
            ->in(__DIR__ . '/tests')
            ->notPath('cache') // Note: The pattern is seen relative from one of the `->in()` directories. And works for files to this way.
    )
    ->setRules($fixers)
    ;
