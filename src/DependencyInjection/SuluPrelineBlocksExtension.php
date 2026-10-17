<?php

declare(strict_types=1);

namespace Totoglu\SuluPrelineBlocksBundle\DependencyInjection;

use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Extension\Extension;
use Symfony\Component\DependencyInjection\Extension\PrependExtensionInterface;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;

final class SuluPrelineBlocksExtension extends Extension implements PrependExtensionInterface
{
    public function prepend(ContainerBuilder $container): void
    {
        if ($container->hasExtension('sulu_admin')) {
            $container->prependExtensionConfig('sulu_admin', [
                'templates' => [
                    'block' => [
                        'directories' => [
                            'sulu_preline_blocks' => \dirname(__DIR__, 2) . '/config/templates/blocks',
                        ],
                    ],
                ],
            ]);
        }

        if ($container->hasExtension('twig')) {
            $container->prependExtensionConfig('twig', [
                'paths' => [
                    \dirname(__DIR__, 2) . '/templates' => 'SuluPrelineBlocks',
                ],
            ]);
        }

        if ($container->hasExtension('twig_component')) {
            $container->prependExtensionConfig('twig_component', [
                'defaults' => [
                    'Totoglu\\SuluPrelineBlocksBundle\\Twig\\Components\\' => '@SuluPrelineBlocks/components/',
                ],
            ]);
        }

        if ($container->hasExtension('sulu_block_suite')) {
            $container->prependExtensionConfig('sulu_block_suite', [
                'rendering' => [
                    'template_namespaces' => [
                        '@SuluPrelineBlocks/blocks' => 200,
                    ],
                ],
            ]);
        }
    }

    /**
     * @param array<array-key, mixed> $configs
     */
    public function load(array $configs, ContainerBuilder $container): void
    {
        $configuration = new Configuration();
        $config = $this->processConfiguration($configuration, $configs);

        $container->setParameter('sulu_preline_blocks.enabled', (bool) $config['enabled']);

        $loader = new YamlFileLoader($container, new FileLocator(\dirname(__DIR__, 2) . '/config'));
        $loader->load('services.yaml');
    }
}
