<?php

$finder = (new PhpCsFixer\Finder())
    ->in('src')
    ->in('tests')
    ->exclude('var')
;

return (new PhpCsFixer\Config())
    ->setRiskyAllowed(true)
    ->setRules([
        '@Symfony' => true,
        'binary_operator_spaces' => true,
        'declare_strict_types' => true,
        'blank_line_before_statement' => true,
        'cast_spaces' => true,
        'include' => true,
        'class_attributes_separation' => true,
        'no_blank_lines_after_class_opening' => true,
        'no_blank_lines_after_phpdoc' => true,
        'no_empty_statement' => true,
        'no_extra_blank_lines' => true,
        'no_leading_import_slash' => true,
        'no_leading_namespace_whitespace' => true,
        'no_trailing_comma_in_singleline_array' => true,
        'no_unused_imports' => true,
        'no_whitespace_in_blank_line' => true,
        'object_operator_without_whitespace' => true,
        'phpdoc_align' => true,
        'phpdoc_indent' => true,
        'phpdoc_no_access' => true,
        'phpdoc_no_package' => true,
        'phpdoc_order' => true,
        'phpdoc_scalar' => true,
        'phpdoc_trim' => true,
        'phpdoc_types' => true,
        'single_blank_line_before_namespace' => true,
        'short_scalar_cast' => true,
        'array_syntax' => ['syntax' => 'short'],
        'no_useless_else' => true,
        'ordered_class_elements' => true,
        'standardize_not_equals' => true,
        'ternary_operator_spaces' => true,
        'trailing_comma_in_multiline' => true,
        'yoda_style' => true,
    ])
    ->setFinder($finder)
;
