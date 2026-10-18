<?php

declare(strict_types=1);

namespace Totoglu\SuluPrelineBlocksBundle\Tests\Config;

use PHPUnit\Framework\TestCase;
use Sulu\Bundle\AdminBundle\SuluAdminBundle;
use Symfony\Component\Yaml\Yaml;

final class XmlSchemaValidationTest extends TestCase
{
    public function testAllBundleXmlBlockTemplatesValidateAgainstSuluXsd(): void
    {
        $adminBundleRef = new \ReflectionClass(SuluAdminBundle::class);
        $adminBundleDir = \dirname((string) $adminBundleRef->getFileName());
        $templateXsd = $adminBundleDir . '/Resources/config/schema/template-1.0.xsd';

        $this->assertFileExists($templateXsd);

        $bundleRoot = \dirname(__DIR__, 2);
        $templateXmlFiles = \glob($bundleRoot . '/config/templates/blocks/*.xml') ?: [];
        $this->assertCount(31, $templateXmlFiles);

        foreach ($templateXmlFiles as $xmlFile) {
            $dom = new \DOMDocument();
            $this->assertTrue($dom->load($xmlFile), \sprintf('Failed to load XML "%s".', $xmlFile));
            $dom->xinclude();
            $this->assertTrue(
                $dom->schemaValidate($templateXsd),
                \sprintf('Block template XML "%s" failed Sulu template-1.0.xsd validation.', \basename($xmlFile)),
            );
        }
    }

    public function testAllBundleXmlBlockItemsValidateAgainstSuluXsd(): void
    {
        $adminBundleRef = new \ReflectionClass(SuluAdminBundle::class);
        $adminBundleDir = \dirname((string) $adminBundleRef->getFileName());
        $propertiesXsd = $adminBundleDir . '/Resources/config/schema/properties-1.0.xsd';

        $this->assertFileExists($propertiesXsd);

        $bundleRoot = \dirname(__DIR__, 2);
        $blockItemFiles = \glob($bundleRoot . '/config/templates/block-items/*.xml') ?: [];
        $this->assertCount(16, $blockItemFiles);

        foreach ($blockItemFiles as $xmlFile) {
            $fragmentDom = new \DOMDocument();
            $this->assertTrue($fragmentDom->load($xmlFile), \sprintf('Failed to load XML "%s".', $xmlFile));
            $fragmentDom->xinclude();

            $wrapperDom = new \DOMDocument('1.0', 'UTF-8');
            $wrapperDom->loadXML(
                '<?xml version="1.0" encoding="UTF-8"?>'
                . '<properties xmlns="http://schemas.sulu.io/template/template">'
                . '<block name="items"><types/></block>'
                . '</properties>',
            );

            $typesNode = $wrapperDom->getElementsByTagName('types')->item(0);
            $this->assertNotNull($typesNode);
            $this->assertNotNull($fragmentDom->documentElement);

            $importedNode = $wrapperDom->importNode($fragmentDom->documentElement, true);
            $typesNode->appendChild($importedNode);

            $this->assertTrue(
                $wrapperDom->schemaValidate($propertiesXsd),
                \sprintf('Block item XML "%s" failed Sulu properties-1.0.xsd validation.', \basename($xmlFile)),
            );
        }
    }

    public function testAllBundleXmlFieldFragmentsValidateAgainstSuluXsd(): void
    {
        $adminBundleRef = new \ReflectionClass(SuluAdminBundle::class);
        $adminBundleDir = \dirname((string) $adminBundleRef->getFileName());
        $propertiesXsd = $adminBundleDir . '/Resources/config/schema/properties-1.0.xsd';

        $this->assertFileExists($propertiesXsd);

        $bundleRoot = \dirname(__DIR__, 2);
        $templateFieldFiles = \glob($bundleRoot . '/config/templates/fields/*.xml') ?: [];
        $this->assertCount(12, $templateFieldFiles);

        $templateIncludeFiles = \glob($bundleRoot . '/config/templates/includes/*.xml') ?: [];
        $this->assertCount(1, $templateIncludeFiles);

        $formFieldFiles = \glob($bundleRoot . '/config/forms/fields/*.xml') ?: [];
        $this->assertCount(10, $formFieldFiles);

        $generalSectionFiles = \glob($bundleRoot . '/config/forms/general/*.xml') ?: [];
        $this->assertCount(9, $generalSectionFiles);

        $fieldFiles = \array_merge($templateFieldFiles, $templateIncludeFiles, $formFieldFiles, $generalSectionFiles);

        foreach ($fieldFiles as $xmlFile) {
            $fragmentDom = new \DOMDocument();
            $this->assertTrue($fragmentDom->load($xmlFile), \sprintf('Failed to load XML "%s".', $xmlFile));
            $fragmentDom->xinclude();

            $wrapperDom = new \DOMDocument('1.0', 'UTF-8');
            $wrapperDom->loadXML(
                '<?xml version="1.0" encoding="UTF-8"?>'
                . '<properties xmlns="http://schemas.sulu.io/template/template"/>',
            );

            $this->assertNotNull($wrapperDom->documentElement);
            $this->assertNotNull($fragmentDom->documentElement);

            // Multi-property fragments use a <properties> root and are included via XPointer.
            $fragmentRoot = $fragmentDom->documentElement;
            $fragmentNodes = 'properties' === $fragmentRoot->localName ? \iterator_to_array($fragmentRoot->childNodes) : [$fragmentRoot];
            foreach ($fragmentNodes as $fragmentNode) {
                if ($fragmentNode instanceof \DOMElement) {
                    $wrapperDom->documentElement->appendChild($wrapperDom->importNode($fragmentNode, true));
                }
            }

            $this->assertTrue(
                $wrapperDom->schemaValidate($propertiesXsd),
                \sprintf('Field fragment XML "%s" failed Sulu properties-1.0.xsd validation.', \basename($xmlFile)),
            );
        }
    }

    public function testPrelineBlocksGeneralSectionFieldMatrix(): void
    {
        $bundleRoot = \dirname(__DIR__, 2);
        $blocksDir = $bundleRoot . '/config/templates/blocks/';

        $loadGeneralFields = function (string $block): array {
            $dom = new \DOMDocument();
            $this->assertTrue($dom->load(__DIR__ . '/../../config/templates/blocks/' . $block . '.xml'));
            $dom->xinclude();

            $xpath = new \DOMXPath($dom);
            $xpath->registerNamespace('s', 'http://schemas.sulu.io/template/template');

            $fields = [];
            $nodes = $xpath->query('//s:section[@name="general"]/s:properties/s:property');
            foreach ($nodes ?: [] as $node) {
                if ($node instanceof \DOMElement) {
                    $fields[] = $node->getAttribute('name');
                }
            }

            return $fields;
        };

        $this->assertSame([], $loadGeneralFields('section'));

        foreach (['code', 'countdown'] as $noMaxWidthBlock) {
            $fields = $loadGeneralFields($noMaxWidthBlock);
            $this->assertNotContains('max_width', $fields);
            $this->assertNotContains('block_align', $fields);
        }

        foreach (['code', 'divider', 'map'] as $noTextAlignBlock) {
            $fields = $loadGeneralFields($noTextAlignBlock);
            $this->assertNotContains('text_align', $fields);
        }

        foreach (['gallery', 'grid'] as $collectionBlock) {
            $fields = $loadGeneralFields($collectionBlock);
            $this->assertContains('item_animation', $fields);
            $this->assertContains('container_padding_remove', $fields);
        }

        $this->assertNotContains('item_animation', $loadGeneralFields('headline'));
        $this->assertNotContains('container_padding_remove', $loadGeneralFields('headline'));
        $this->assertContains('container_padding_remove', $loadGeneralFields('video'));
        $this->assertContains('container_padding_remove', $loadGeneralFields('map'));
        $this->assertFileExists($blocksDir . 'headline.xml');
    }

    public function testExampleSnippetsIncludesAndPresetsAreValid(): void
    {
        $adminBundleRef = new \ReflectionClass(SuluAdminBundle::class);
        $adminBundleDir = \dirname((string) $adminBundleRef->getFileName());
        $templateXsd = $adminBundleDir . '/Resources/config/schema/template-1.0.xsd';

        $bundleRoot = \dirname(__DIR__, 2);
        $includeFile = $bundleRoot . '/docs/examples/includes/content_block_types.xml';
        $this->assertFileExists($includeFile);

        $snippetFiles = \glob($bundleRoot . '/docs/examples/snippets/*.xml') ?: [];
        $this->assertCount(3, $snippetFiles);

        foreach ($snippetFiles as $xmlFile) {
            $dom = new \DOMDocument();
            $this->assertTrue($dom->load($xmlFile), \sprintf('Failed to load example snippet XML "%s".', $xmlFile));
            $dom->xinclude();
            $this->assertTrue(
                $dom->schemaValidate($templateXsd),
                \sprintf('Example snippet XML "%s" failed Sulu template-1.0.xsd validation.', \basename($xmlFile)),
            );
        }

        $presetFiles = \glob($bundleRoot . '/docs/examples/presets/*.yaml') ?: [];
        $this->assertCount(16, $presetFiles);

        foreach ($presetFiles as $presetFile) {
            $blockKey = \basename($presetFile, '.yaml');
            $parsed = Yaml::parseFile($presetFile);
            $this->assertIsArray($parsed, \sprintf('Example preset YAML "%s" must parse to an array.', \basename($presetFile)));
            $this->assertArrayHasKey($blockKey, $parsed, \sprintf('Example preset YAML "%s" must contain root key "%s".', \basename($presetFile), $blockKey));
            $this->assertIsArray($parsed[$blockKey]);
            $this->assertArrayHasKey('presets', $parsed[$blockKey]);
            $this->assertIsArray($parsed[$blockKey]['presets']);
            $this->assertNotEmpty($parsed[$blockKey]['presets']);
        }
    }
}
