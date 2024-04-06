<?php

$finder = PhpCsFixer\Finder::create()
    ->in(__DIR__ . '/app')
    ->in(__DIR__ . '/tests')
    ->in(__DIR__ . '/database')
    ->in(__DIR__ . '/routes')
;

$config = new PhpCsFixer\Config();
return $config->setRules([
        '@PSR2' => true,
        'array_syntax' => ['syntax' => 'short'],
        'no_unused_imports' => true,
        'no_whitespace_in_blank_line'       => true,
        'return_type_declaration' => [
            'space_before' => 'none',
        ],
    ])
    ->setFinder($finder);
