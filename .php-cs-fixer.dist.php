<?php

use PhpCsFixer\Config;
use PhpCsFixer\Finder;

return (new Config())
    ->setRiskyAllowed(true)
    ->setRules([
        '@PSR12' => true,
        '@PHP81Migration' => true,
        '@PHP80Migration:risky' => true,
        'phpdoc_align' => false,
        'phpdoc_summary' => false,
        'concat_space' => ['spacing' => 'one'],
        'multiline_whitespace_before_semicolons' => true,
        'no_useless_else' => true,
        'no_useless_return' => true,
        'ordered_imports' => true,
        'phpdoc_order' => true,
        'array_syntax' => ['syntax' => 'short'],
        'echo_tag_syntax' => ['format' => 'long', 'long_function' => 'echo'],
        'increment_style' => ['style' => 'post'],
        'yoda_style' => false,
        'dir_constant' => true,
        'declare_strict_types' => true,
        'blank_line_before_statement' => ['statements' => ['break', 'continue', 'declare', 'return', 'throw', 'try']],
    ])
    ->setFinder(
        Finder::create()
            ->exclude('bin')
            ->exclude('coverage')
            ->exclude('vendor')
            ->in(__DIR__)
    )
    ;
