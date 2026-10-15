<?php

declare(strict_types=1);

namespace Totoglu\SuluPrelineBlocksBundle\Tests\Config;

use PHPUnit\Framework\TestCase;
use Sulu\Bundle\AdminBundle\SuluAdminBundle;

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
        $this->assertCount(33, $templateXmlFiles);

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
        $this->assertCount(17, $blockItemFiles);

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
        $fieldFiles = \glob($bundleRoot . '/config/templates/fields/*.xml') ?: [];
        $this->assertCount(12, $fieldFiles);

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

            $importedNode = $wrapperDom->importNode($fragmentDom->documentElement, true);
            $wrapperDom->documentElement->appendChild($importedNode);

            $this->assertTrue(
                $wrapperDom->schemaValidate($propertiesXsd),
                \sprintf('Field fragment XML "%s" failed Sulu properties-1.0.xsd validation.', \basename($xmlFile)),
            );
        }
    }
}
