<?php

// get a list of all files ending with ".php" in the entire project
$finder = PhpCsFixer\Finder::create()->ignoreDotFiles(false)->in(__DIR__);

return (new PhpCsFixer\Config())->setRules([
        '@PSR12' => true,
        // 'strict_param' => true,
        'array_syntax' => ['syntax' => 'short'],
    ])
    ->setFinder($finder);
