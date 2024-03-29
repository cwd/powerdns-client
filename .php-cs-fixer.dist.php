<?php

/*
 * This file is part of the CwdPowerDNS Client
 *
 * (c) 2017 cwd.at GmbH <office@cwd.at>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

$finder = (new PhpCsFixer\Finder())
    ->notName('*.twig')
    ->in([__DIR__])
;

$year = date('Y');

return (new PhpCsFixer\Config())
    ->setUsingCache(true)
    ->setRiskyAllowed(true)
    ->setRules([
        '@Symfony' => true,
        'declare_strict_types' => true,
        'header_comment' => [
            'header' => <<<EOF
This file is part of the CwdPowerDNS Client

(c) {$year} cwd.at GmbH <office@cwd.at>

For the full copyright and license information, please view the LICENSE
file that was distributed with this source code.
EOF
            ,
            'location' => 'after_open',
        ],
    ])
    ->setFinder($finder)
;
