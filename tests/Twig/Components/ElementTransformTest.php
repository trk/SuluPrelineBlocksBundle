<?php

declare(strict_types=1);

namespace Totoglu\SuluPrelineBlocksBundle\Tests\Twig\Components;

use PHPUnit\Framework\TestCase;
use Totoglu\SuluBlockSuiteBundle\Security\CssScoper;
use Totoglu\SuluBlockSuiteBundle\Transformer\DefaultAttributesTransformer;
use Totoglu\SuluPrelineBlocksBundle\Twig\Components\ElementTransform;

final class ElementTransformTest extends TestCase
{
    public function testMarginAndPadding(): void
    {
        $this->assertSame('', ElementTransform::margin([]));
        $this->assertSame('mt-4 mb-6', ElementTransform::margin(['margin_top' => 'sm', 'margin_bottom' => 'md']));
        $this->assertSame('mt-0 mb-auto', ElementTransform::margin(['margin_top' => 'remove', 'margin_bottom' => 'auto']));
        $this->assertSame('', ElementTransform::margin(['position' => 'absolute', 'margin_top' => 'lg', 'margin_bottom' => 'lg']));
        $this->assertSame('my-4', ElementTransform::margin(['margin' => 'sm']));
        $this->assertSame('mt-0 mb-6', ElementTransform::margin(['margin' => 'md', 'margin_remove_top' => true]));
        $this->assertSame('mt-6 mb-0', ElementTransform::margin(['margin' => 'md', 'margin_remove_bottom' => true]));
        $this->assertSame('my-0', ElementTransform::margin(['margin' => 'md', 'margin_remove_top' => true, 'margin_remove_bottom' => true]));
        $this->assertSame('mt-0', ElementTransform::margin(['margin_remove_top' => true]));
        $this->assertSame('mb-0', ElementTransform::margin(['margin_remove_bottom' => true]));

        $this->assertSame('', ElementTransform::padding([]));
        $this->assertSame('p-6 sm:p-8', ElementTransform::padding(['padding' => 'md']));
        $this->assertSame('p-6 sm:p-8 pt-0 sm:pt-0', ElementTransform::padding(['padding' => 'md', 'padding_remove_top' => true]));
        $this->assertSame('p-6 sm:p-8 pb-0 sm:pb-0', ElementTransform::padding(['padding' => 'md', 'padding_remove_bottom' => true]));
        $this->assertSame('py-0', ElementTransform::padding(['padding' => 'lg', 'padding_remove_top' => true, 'padding_remove_bottom' => true], 'default', 'padding', 'section_y'));

        $this->assertSame('', ElementTransform::containerPaddingRemove([]));
        $this->assertSame('px-0', ElementTransform::containerPaddingRemove(['container_padding_remove' => true]));
        $this->assertSame('', ElementTransform::containerPaddingRemove(['container_padding_remove' => true, 'position' => 'absolute']));
    }

    public function testMaxWidth(): void
    {
        $this->assertSame('', ElementTransform::maxWidth([]));
        $this->assertSame('', ElementTransform::maxWidth(['max_width' => 'none']));
        $this->assertSame('max-w-md', ElementTransform::maxWidth(['max_width' => 'md']));
        $this->assertSame('max-w-2xl', ElementTransform::maxWidth(['maxwidth' => '2xl']));
        $this->assertSame('max-w-full', ElementTransform::maxWidth(['max_width' => 'full']));
        $this->assertSame('lg:max-w-2xl', ElementTransform::maxWidth(['max_width' => '2xl', 'max_width_breakpoint' => 'lg']));
    }

    public function testBlockAlign(): void
    {
        $this->assertSame('', ElementTransform::blockAlign([]));
        $this->assertSame('mx-auto', ElementTransform::blockAlign(['block_align' => 'center']));
        $this->assertSame('ml-auto', ElementTransform::blockAlign(['block_align' => 'right']));
        $this->assertSame('mr-auto', ElementTransform::blockAlign(['block_align' => 'left']));
        $this->assertSame(
            'ml-auto md:mx-auto',
            ElementTransform::blockAlign([
                'block_align' => 'center',
                'block_align_breakpoint' => 'md',
                'block_align_fallback' => 'right',
            ]),
        );
    }

    public function testTextAlign(): void
    {
        $this->assertSame('', ElementTransform::textAlign([]));
        $this->assertSame('text-center', ElementTransform::textAlign(['text_align' => 'center']));
        $this->assertSame('text-right', ElementTransform::textAlign(['text_align' => 'right']));
        $this->assertSame('text-justify', ElementTransform::textAlign(['text_align' => 'justify']));
        $this->assertSame('text-left', ElementTransform::textAlign(['text_align' => 'left']));
        $this->assertSame(
            'text-center lg:text-left',
            ElementTransform::textAlign([
                'text_align' => 'left',
                'text_align_breakpoint' => 'lg',
                'text_align_fallback' => 'center',
            ]),
        );
    }

    public function testPositionAndBlendAndAnimation(): void
    {
        $this->assertSame('', ElementTransform::position([]));
        $this->assertSame('relative z-20', ElementTransform::position(['position' => 'relative', 'position_z_index' => '2']));
        $this->assertSame('', ElementTransform::resolvePositionStyle([]));
        $this->assertSame('', ElementTransform::resolvePositionStyle(['position_left' => '20']));
        $this->assertSame(
            'left: 20px; top: -10%;',
            ElementTransform::resolvePositionStyle([
                'position' => 'relative',
                'position_left' => '20',
                'position_top' => '-10%',
            ]),
        );

        $this->assertSame('', ElementTransform::blendMode([]));
        $this->assertSame('mix-blend-multiply', ElementTransform::blendMode(['blend' => true]));
        $this->assertSame('mix-blend-screen', ElementTransform::blendMode(['blend_mode' => 'screen']));

        $this->assertSame('', ElementTransform::animation([]));
        $this->assertSame('', ElementTransform::animation(['animation' => 'parallax']));
        $this->assertStringContainsString('-translate-y-2', ElementTransform::animation(['animation' => 'slide-top-small']));
        $this->assertSame('will-change-transform transition-transform duration-500 ease-out', ElementTransform::parallax(['animation' => 'parallax', 'parallax' => 'medium']));
        $this->assertSame(['data-parallax' => 'medium'], ElementTransform::resolveParallaxAttributes(['animation' => 'parallax', 'parallax' => 'medium']));
    }

    public function testVisibility(): void
    {
        $this->assertSame('', ElementTransform::visibility([]));
        $this->assertSame('', ElementTransform::visibility(['visibility' => 'all']));
        $this->assertSame('hidden sm:block', ElementTransform::visibility(['visibility' => 'sm']));
        $this->assertSame('hidden md:block', ElementTransform::visibility(['visibility' => 'md']));
        $this->assertSame('sm:hidden', ElementTransform::visibility(['visibility' => 'hidden-sm']));
        $this->assertSame('md:hidden', ElementTransform::visibility(['visibility' => 'hidden-md']));
    }

    public function testCustomClassAndId(): void
    {
        $this->assertSame('', ElementTransform::customClass([]));
        $this->assertSame('my-custom-class', ElementTransform::customClass(['custom_class' => 'my-custom-class']));
        $this->assertSame('class-override', ElementTransform::customClass(['class' => 'class-override']));

        $this->assertNull(ElementTransform::resolveId([]));
        $this->assertSame('hero-section', ElementTransform::resolveId(['id' => 'hero-section']));
        $this->assertNull(ElementTransform::resolveId(['id' => "bad');alert(1)//"]));
        $this->assertNull(ElementTransform::resolveId(['id' => '123-starts-with-digit']));
    }

    public function testWrapperClasses(): void
    {
        $props = [
            'margin_top' => 'remove',
            'margin_bottom' => 'lg',
            'max_width' => '4xl',
            'max_width_breakpoint' => 'md',
            'block_align' => 'center',
            'text_align' => 'center',
            'visibility' => 'sm',
            'blend' => true,
            'container_padding_remove' => true,
            'custom_class' => 'shadow-2xl',
        ];

        $result = ElementTransform::wrapperClasses($props, 'base-component');

        $this->assertStringContainsString('base-component', $result);
        $this->assertStringContainsString('mt-0', $result);
        $this->assertStringContainsString('mb-10', $result);
        $this->assertStringContainsString('px-0', $result);
        $this->assertStringContainsString('md:max-w-4xl', $result);
        $this->assertStringContainsString('mx-auto', $result);
        $this->assertStringContainsString('text-center', $result);
        $this->assertStringContainsString('hidden sm:block', $result);
        $this->assertStringContainsString('mix-blend-multiply', $result);
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

        $arrayAttrs = ElementTransform::resolveCustomAttributes([
            '_attributes' => [
                'id' => 'bad',
                'onclick' => 'alert(1)',
                'href' => 'javascript:alert(1)',
                'data-valid' => 'yes',
            ],
        ]);
        $this->assertSame(['data-valid' => 'yes'], $arrayAttrs);

        $preScoped = ElementTransform::resolveScopedCss([
            '_scoped_css' => '#x { color: red; }</sty</style>le><script>alert(1)</script>',
        ]);
        $this->assertSame('#x { color: red; }', $preScoped);
    }

    public function testConsumesCorePipelineKeys(): void
    {
        $block = (new DefaultAttributesTransformer())->transform([
            'type' => 'card',
            'props' => [
                'class' => '  shadow-lg   p-6 shadow-lg ',
                'attributes' => "data-aos=fade-up\nonclick=evil()",
                'css' => '.el-element { color: red; }',
            ],
        ], []);

        $this->assertIsString($block['_id']);
        $this->assertSame($block['_id'], ElementTransform::resolveId($block, ['seed-is-ignored']));
        $this->assertSame($block['_class'], ElementTransform::customClass($block));
        $this->assertSame('shadow-lg p-6', ElementTransform::customClass($block));
        $this->assertSame($block['_scoped_css'], ElementTransform::resolveScopedCss($block, $block['_id']));
        $this->assertSame(['data-aos' => 'fade-up'], ElementTransform::resolveCustomAttributes($block));
    }

    public function testCoreKeysTakePrecedenceOverRawProps(): void
    {
        $props = [
            '_id' => 'el-core1234',
            'id' => 'raw-id',
            '_class' => 'core-class',
            'class' => 'raw-class',
            '_scoped_css' => '#el-core1234 { color: red; }',
            'css' => 'color: blue;',
        ];

        $this->assertSame('el-core1234', ElementTransform::resolveId($props));
        $this->assertSame('core-class', ElementTransform::customClass($props));
        $this->assertSame('#el-core1234 { color: red; }', ElementTransform::resolveScopedCss($props));
    }

    public function testFallsBackToCoreUtilitiesWithoutPipeline(): void
    {
        $props = ['css' => 'color: red;'];
        $seed = ['component', ['title' => 'x'], 0];
        $expectedId = CssScoper::generateElementId([$seed, $props]);

        $this->assertSame($expectedId, ElementTransform::resolveId($props, $seed));
        $this->assertSame(CssScoper::generateElementId($props), ElementTransform::resolveId($props));
        $this->assertSame('#' . $expectedId . ' { color: red; }', ElementTransform::resolveScopedCss($props, $expectedId));
        $this->assertSame('a b', ElementTransform::customClass(['class' => ' a  b a ']));
        $this->assertNull(ElementTransform::resolveId(['_id' => 'bad id']));
    }
}

