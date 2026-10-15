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
        $this->assertNotEmpty($templateXmlFiles);

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
}
