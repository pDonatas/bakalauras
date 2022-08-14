<?php
$finder = PhpCsFixer\Finder::create()
    ->in(__DIR__)
;

$config = new PhpCsFixer\Config();
return $config->setRules([
    '@PSR12' => true,
    '@Symfony' => true,
    'phpdoc_to_comment' => false,
    'single_line_comment_spacing' => false
])
    ->setFinder($finder)
    ;
