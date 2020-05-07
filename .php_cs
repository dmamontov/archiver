<?php

return PhpCsFixer\Config::create()
    ->setRiskyAllowed(true)
    ->setRules([
        '@PSR2' => true,
        '@PhpCsFixer' => true,
        'psr4' => true,
        'single_line_comment_style' => false,
    ])
    ->setFinder(
        PhpCsFixer\Finder::create()->name('*.php')->in(__DIR__.'/src')
    )
;
