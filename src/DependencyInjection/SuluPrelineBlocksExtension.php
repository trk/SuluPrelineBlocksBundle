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
                    '%kernel.project_dir%/templates' => null,
                    \dirname(__DIR__, 2) . '/templates' => 'SuluPrelineBlocks',
                    \dirname(__DIR__, 2) . '/templates' => null,
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
            $blockSuiteRoot = \dirname((string) (new \ReflectionClass(\Totoglu\SuluBlockSuiteBundle\SuluBlockSuiteBundle::class))->getFileName(), 2);

            $container->prependExtensionConfig('sulu_block_suite', [
                'rendering' => [
                    'template_namespaces' => [
                        'blocks',
                        '@SuluPrelineBlocks/blocks',
                        '@SuluBlockSuite/blocks',
                    ],
                ],
                'props' => [
                    'schema_dir' => [
                        $blockSuiteRoot . '/config/blocks/props',
                        \dirname(__DIR__, 2) . '/config/blocks/props',
                        '%kernel.project_dir%/config/blocks/props',
                    ],
                ],
                'layout' => [
                    'mappings' => [
                        'container_width' => [
                            'full' => 'w-full',
                            'boxed' => 'max-w-7xl mx-auto px-4 sm:px-6 lg:px-8',
                            'narrow' => 'max-w-4xl mx-auto px-4 sm:px-6',
                        ],
                        'content_padding' => [
                            'none' => 'py-0',
                            'compact' => 'py-6 md:py-8',
                            'normal' => 'py-12 md:py-16',
                            'spacious' => 'py-16 md:py-24',
                        ],
                        'margin_top' => [
                            'none' => 'mt-0',
                            'xs' => 'mt-2',
                            'sm' => 'mt-4',
                            'md' => 'mt-6',
                            'lg' => 'mt-10',
                            'xl' => 'mt-16',
                            'auto' => 'mt-auto',
                        ],
                        'margin_bottom' => [
                            'none' => 'mb-0',
                            'xs' => 'mb-2',
                            'sm' => 'mb-4',
                            'md' => 'mb-6',
                            'lg' => 'mb-10',
                            'xl' => 'mb-16',
                            'auto' => 'mb-auto',
                        ],
                        'max_width' => [
                            'small' => 'max-w-xl mx-auto',
                            'medium' => 'max-w-3xl mx-auto',
                            'large' => 'max-w-5xl mx-auto',
                            'xlarge' => 'max-w-7xl mx-auto',
                            '2xlarge' => 'max-w-(--breakpoint-2xl) mx-auto',
                        ],
                        'block_align' => [
                            'left' => 'text-left',
                            'center' => 'text-center mx-auto',
                            'right' => 'text-right ml-auto',
                        ],
                    ],
                ],
            ]);
        }
    }

    /**
     * @param array<string, mixed> $configs
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
