<?php

declare(strict_types=1);

namespace Totoglu\SuluPrelineBlocksBundle\Tests\DependencyInjection;

use PHPUnit\Framework\TestCase;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Extension\Extension;
use Totoglu\SuluPrelineBlocksBundle\DependencyInjection\SuluPrelineBlocksExtension;
use Totoglu\SuluPrelineBlocksBundle\Transformer\PrelineBlockTransformer;
use Totoglu\SuluPrelineBlocksBundle\Transformer\SectionFlattener;

final class SuluPrelineBlocksExtensionTest extends TestCase
{
    public function testPrependRegistersPriorityTemplateNamespaceAndScopedTwigPathOnly(): void
    {
        $container = new ContainerBuilder();
        foreach (['sulu_admin', 'twig', 'twig_component', 'sulu_block_suite'] as $alias) {
            $container->registerExtension(new class($alias) extends Extension {
                public function __construct(private readonly string $alias)
                {
                }

                public function load(array $configs, ContainerBuilder $container): void
                {
                }

                public function getAlias(): string
                {
                    return $this->alias;
                }
            });
        }

        $extension = new SuluPrelineBlocksExtension();
        $extension->prepend($container);

        $twigConfigs = $container->getExtensionConfig('twig');
        self::assertCount(1, $twigConfigs);
        /** @var array{paths: array<string, string|null>} $twigConfig */
        $twigConfig = $twigConfigs[0];
        self::assertArrayNotHasKey('%kernel.project_dir%/templates', $twigConfig['paths']);
        self::assertSame(['SuluPrelineBlocks'], \array_values($twigConfig['paths']));

        $blockSuiteConfigs = $container->getExtensionConfig('sulu_block_suite');
        self::assertCount(1, $blockSuiteConfigs);
        self::assertSame(
            [
                'rendering' => [
                    'template_namespaces' => [
                        '@SuluPrelineBlocks/blocks' => 200,
                    ],
                ],
            ],
            $blockSuiteConfigs[0],
        );
    }

    public function testLoadRegistersServicesWithAutoconfigurationWithoutManualTags(): void
    {
        $container = new ContainerBuilder();
        $extension = new SuluPrelineBlocksExtension();
        $extension->load([], $container);

        $transformerDef = $container->getDefinition(PrelineBlockTransformer::class);
        self::assertTrue($transformerDef->isAutoconfigured());
        self::assertSame([], $transformerDef->getTags());

        $flattenerDef = $container->getDefinition(SectionFlattener::class);
        self::assertTrue($flattenerDef->isAutoconfigured());
        self::assertSame([], $flattenerDef->getTags());
    }
}
