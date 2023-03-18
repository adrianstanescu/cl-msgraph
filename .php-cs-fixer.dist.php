<?php

$finder = PhpCsFixer\Finder::create()
    ->in(__DIR__ . '/src')
    ->in(__DIR__ . '/tests')
    ->name('*.php');

$config = new PhpCsFixer\Config();

return $config->setRules([
    // Rule Set
    '@PhpCsFixer' => true,
    // Basic
    'braces' => [
        'position_after_functions_and_oop_constructs' => 'same',
    ],
    // Class Notation
    'no_null_property_initialization' => false,
    // Control Structure
    // 'yoda_style' => false,
    'yoda_style' => [
        'equal' => false,
        'identical' => false,
    ],
    // Import
    'global_namespace_import' => [
        'import_classes' => true,
        'import_constants' => true,
        'import_functions' => null,
    ],
    'ordered_imports' => [
        'sort_algorithm' => 'alpha',
        'imports_order' => ['const', 'class', 'function'],
    ],
    'concat_space' => ['spacing' => 'one'],
    'echo_tag_syntax' => false,
    'semicolon_after_instruction' => false,
    'no_alternative_syntax' => ['fix_non_monolithic_code' => false],
    'php_unit_test_class_requires_covers' => false,
])
    ->setFinder($finder);
