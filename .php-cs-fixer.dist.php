<?php

declare(strict_types=1);

use PhpCsFixer\Config;
use PhpCsFixer\Finder;

$finder = Finder::create()
    ->in(__DIR__ . '/src')
    ->name('*.php')
    ->ignoreDotFiles(true)
    ->ignoreVCS(true);

return (new Config())
    ->setRiskyAllowed(true)
    ->setRules([
        '@PSR12' => true,

        // Strictness
        'declare_strict_types' => true,
        'strict_param' => true,

        // Imports
        'ordered_imports' => true,
        'no_unused_imports' => true,
        'single_import_per_statement' => true,

        // Clean code
        'no_extra_blank_lines' => true,
        'no_trailing_whitespace' => true,
        'no_whitespace_in_blank_line' => true,
        'blank_line_after_namespace' => true,
        'blank_line_after_opening_tag' => true,

        // Arrays
        'array_syntax' => ['syntax' => 'short'],

        // PHPDoc
        'phpdoc_align' => ['align' => 'left'],
        'phpdoc_scalar' => true,
        'phpdoc_trim' => true,
        'phpdoc_no_empty_return' => true,

        // Control structure
        'control_structure_braces' => true,
        'braces_position' => [
            'classes_opening_brace' => 'next_line_unless_newline_at_signature_end',
            'functions_opening_brace' => 'next_line_unless_newline_at_signature_end',
        ],
    ])
    ->setFinder($finder);