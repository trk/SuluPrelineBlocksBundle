<?php

declare(strict_types=1);

namespace Totoglu\SuluPrelineBlocksBundle\Tests\Twig\Components;

use PHPUnit\Framework\TestCase;
use Totoglu\SuluPrelineBlocksBundle\Twig\Components\ElementTransform;

final class ElementTransformTest extends TestCase
{
    public function testMargin(): void
    {
        $this->assertSame('', ElementTransform::margin([]));
        $this->assertSame('mt-4 mb-6', ElementTransform::margin(['margin_top' => 'sm', 'margin_bottom' => 'md']));
        $this->assertSame('mt-0 mb-auto', ElementTransform::margin(['margin_top' => 'none', 'margin_bottom' => 'auto']));
        $this->assertSame('mt-2 mb-16', ElementTransform::margin(['margin_top' => 'xsmall', 'margin_bottom' => 'xlarge']));
    }

    public function testMaxWidth(): void
    {
        $this->assertSame('', ElementTransform::maxWidth([]));
        $this->assertSame('', ElementTransform::maxWidth(['max_width' => 'none']));
        $this->assertSame('max-w-md', ElementTransform::maxWidth(['max_width' => 'md']));
        $this->assertSame('max-w-2xl', ElementTransform::maxWidth(['maxwidth' => '2xl']));
        $this->assertSame('max-w-full', ElementTransform::maxWidth(['max_width' => 'full']));
    }

    public function testBlockAlign(): void
    {
        $this->assertSame('', ElementTransform::blockAlign([]));
        $this->assertSame('mx-auto', ElementTransform::blockAlign(['block_align' => 'center']));
        $this->assertSame('ml-auto', ElementTransform::blockAlign(['block_align' => 'right']));
        $this->assertSame('mr-auto', ElementTransform::blockAlign(['block_align' => 'left']));
    }

    public function testTextAlign(): void
    {
        $this->assertSame('', ElementTransform::textAlign([]));
        $this->assertSame('text-center', ElementTransform::textAlign(['text_align' => 'center']));
        $this->assertSame('text-right', ElementTransform::textAlign(['text_align' => 'right']));
        $this->assertSame('text-justify', ElementTransform::textAlign(['text_align' => 'justify']));
        $this->assertSame('text-left', ElementTransform::textAlign(['text_align' => 'left']));
    }

    public function testVisibility(): void
    {
        $this->assertSame('', ElementTransform::visibility([]));
        $this->assertSame('', ElementTransform::visibility(['visibility' => 'all']));
        $this->assertSame('hidden sm:block', ElementTransform::visibility(['visibility' => 'hidden_sm']));
        $this->assertSame('hidden md:block', ElementTransform::visibility(['visibility' => 'hidden_md']));
        $this->assertSame('block md:hidden', ElementTransform::visibility(['visibility' => 'visible_md']));
    }

    public function testCustomClassAndId(): void
    {
        $this->assertSame('', ElementTransform::customClass([]));
        $this->assertSame('my-custom-class', ElementTransform::customClass(['custom_class' => 'my-custom-class']));
        $this->assertSame('class-override', ElementTransform::customClass(['class' => 'class-override']));

        $this->assertNull(ElementTransform::resolveId([]));
        $this->assertSame('hero-section', ElementTransform::resolveId(['id' => 'hero-section']));
    }

    public function testWrapperClasses(): void
    {
        $props = [
            'margin_top' => 'md',
            'margin_bottom' => 'lg',
            'max_width' => '4xl',
            'block_align' => 'center',
            'text_align' => 'center',
            'visibility' => 'hidden_sm',
            'custom_class' => 'shadow-2xl',
        ];

        $result = ElementTransform::wrapperClasses($props, 'base-component');

        $this->assertStringContainsString('base-component', $result);
        $this->assertStringContainsString('mt-6', $result);
        $this->assertStringContainsString('mb-10', $result);
        $this->assertStringContainsString('max-w-4xl', $result);
        $this->assertStringContainsString('mx-auto', $result);
        $this->assertStringContainsString('text-center', $result);
        $this->assertStringContainsString('hidden sm:block', $result);
        $this->assertStringContainsString('shadow-2xl', $result);
    }

    public function testCustomAttributesAndScopedCss(): void
    {
        $props = [
            'id' => 'feature-card',
            'attributes' => "data-hs-overlay=\"#modal-1\"\naria-controls=\"panel-1\"\nonclick=\"evil()\"",
            'css' => '.el-element { background: #111; }',
        ];

        $attrs = ElementTransform::resolveCustomAttributes($props);
        $this->assertSame('#modal-1', $attrs['data-hs-overlay'] ?? null);
        $this->assertSame('panel-1', $attrs['aria-controls'] ?? null);
        $this->assertArrayNotHasKey('onclick', $attrs);

        $scopedCss = ElementTransform::resolveScopedCss($props);
        $this->assertSame('#feature-card { background: #111; }', $scopedCss);
    }
}


