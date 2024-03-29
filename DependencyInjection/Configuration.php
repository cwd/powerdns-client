<?php

/*
 * This file is part of the CwdPowerDNS Client
 *
 * (c) 2024 cwd.at GmbH <office@cwd.at>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Cwd\PowerDNSClient\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

/**
 * This is the class that validates and merges configuration from your app/config files.
 *
 * To learn more see {@link http://symfony.com/doc/current/cookbook/bundles/configuration.html}
 */
class Configuration implements ConfigurationInterface
{
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder('cwd_power_dns_client');
        $rootNode = $treeBuilder->getRootNode();
        $rootNode->children()
            ->variableNode('hosts')->defaultValue([])->end()

            ->variableNode('uri')->defaultValue('http://localhost')->end()
            ->variableNode('api_key')->defaultValue(null)->end()
            ->variableNode('default_server')->defaultValue('localhost')->end()
        ->end();

        return $treeBuilder;
    }
}
