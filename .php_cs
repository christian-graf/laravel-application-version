<?php

$fixers = [
  '@Symfony' => true,
  'array_syntax' => array('syntax' => 'short'),
  'combine_consecutive_unsets' => true,
  'no_useless_else' => true,
  'no_useless_return' => true,
  'ordered_imports' => ['sortAlgorithm' => 'length'],
  'phpdoc_indent' => false,
  'phpdoc_annotation_without_dot' => false,
  'phpdoc_no_empty_return' => false,
  'concat_space' => [
    'spacing' => 'one',
  ],
  'yoda_style' => false,
];

return PhpCsFixer\Config::create()
  ->setFinder(
    PhpCsFixer\Finder::create()
      ->in(__DIR__ . '/src')
      ->in(__DIR__ . '/tests')

      // Note: The pattern is seen relative from one of the `->in()`
      // directories. And works for files too this way.
      //->notPath('exampleDirectory/exampleSubDirectory/')
  )
  ->setRules($fixers);
