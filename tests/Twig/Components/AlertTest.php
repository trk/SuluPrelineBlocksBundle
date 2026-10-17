<?php

declare(strict_types=1);

namespace Totoglu\SuluPrelineBlocksBundle\Tests\Twig\Components;

use PHPUnit\Framework\TestCase;
use Totoglu\SuluPrelineBlocksBundle\Twig\Components\Alert;

final class AlertTest extends TestCase
{
    public function testDefaultValues(): void
    {
        $alert = new Alert();

        self::assertNull($alert->get('title'));
        self::assertNull($alert->get('content'));
        self::assertNull($alert->get('icon'));
        self::assertSame('info', $alert->getAlertStyle());
        self::assertSame('soft', $alert->getAlertVariant());
        self::assertFalse($alert->isDismissible());
        self::assertSame('ti ti-info-circle', $alert->getResolvedIcon());
        self::assertSame('text-base font-semibold', $alert->getTitleSizeClasses());
        self::assertSame('rounded-xl', $alert->getRoundedClasses());
        self::assertStringContainsString('bg-primary-50', $alert->getAlertClasses());
    }

    public function testStylesAndVariants(): void
    {
        $alert = new Alert();

        $alert->mergeProps(['alert_style' => 'success', 'alert_variant' => 'solid']);
        self::assertSame('success', $alert->getAlertStyle());
        self::assertSame('solid', $alert->getAlertVariant());
        self::assertStringContainsString('bg-emerald-600', $alert->getAlertClasses());
        self::assertSame('ti ti-circle-check', $alert->getResolvedIcon());

        $alert->mergeProps(['alert_style' => 'warning', 'alert_variant' => 'outline']);
        self::assertSame('warning', $alert->getAlertStyle());
        self::assertSame('outline', $alert->getAlertVariant());
        self::assertStringContainsString('border-2 border-amber-500', $alert->getAlertClasses());
        self::assertSame('ti ti-alert-triangle', $alert->getResolvedIcon());

        $alert->mergeProps(['alert_style' => 'danger', 'alert_variant' => 'glass']);
        self::assertSame('danger', $alert->getAlertStyle());
        self::assertSame('glass', $alert->getAlertVariant());
        self::assertStringContainsString('backdrop-blur-md bg-destructive/10', $alert->getAlertClasses());
        self::assertSame('ti ti-alert-circle', $alert->getResolvedIcon());

        $alert->mergeProps(['alert_style' => 'dark', 'alert_variant' => 'soft']);
        self::assertSame('dark', $alert->getAlertStyle());
        self::assertSame('ti ti-bell', $alert->getResolvedIcon());
    }

    public function testDismissible(): void
    {
        $alert = new Alert();
        self::assertFalse($alert->isDismissible());

        $alert->assign('dismissible', 'yes');
        self::assertTrue($alert->isDismissible());

        $alertSnake = new Alert();
        $alertSnake->mergeProps(['dismissible' => 'yes']);
        self::assertTrue($alertSnake->isDismissible());
    }

    public function testIconHandling(): void
    {
        $alert = new Alert();

        // Custom icon override
        $alert->assign('icon', 'shield-check');
        self::assertSame('ti ti-shield-check', $alert->getResolvedIcon());

        // Custom icon array
        $alert->assign('icon', ['icon' => 'ti ti-sparkles']);
        self::assertSame('ti ti-sparkles', $alert->getResolvedIcon());

        // Disabled icon
        $alert->mergeProps(['icon_display' => 'none']);
        self::assertNull($alert->getResolvedIcon());
    }

    public function testTitleAndContent(): void
    {
        $alert = new Alert();
        $alert->assign('title', '  Important Notice  ');
        $alert->assign('content', '<p>System maintenance tonight.</p>');

        self::assertSame('Important Notice', $alert->getTitle());
        self::assertSame('<p>System maintenance tonight.</p>', $alert->getContent());
    }

    public function testLinkAndButtonText(): void
    {
        $alert = new Alert();
        $alert->assign('link', [
            'url' => 'https://example.com/docs',
            'target' => '_blank',
            'title' => 'Documentation Tooltip',
        ]);
        $alert->assign('link_title', 'Read Documentation');

        self::assertSame('https://example.com/docs', $alert->getLinkUrl());
        self::assertSame('_blank', $alert->getLinkTarget());
        self::assertSame('Read Documentation', $alert->getButtonText());
        self::assertSame('Documentation Tooltip', $alert->getLinkTitle());
    }

    public function testWrapperClassesAndId(): void
    {
        $alert = new Alert();
        $alert->mergeProps([
            'id' => 'system-alert',
            'custom_class' => 'my-alert',
            'margin_top' => 'md',
            'margin_bottom' => 'lg',
            'padding' => 'lg',
            'rounded' => '2xl',
        ]);

        self::assertSame('system-alert', $alert->getElementId());
        $wrapper = $alert->getWrapperClasses();
        self::assertStringNotContainsString('relative flex items-start', $wrapper);
        self::assertStringNotContainsString('rounded-2xl', $wrapper);
        self::assertStringNotContainsString('p-8', $wrapper);
        self::assertStringContainsString('mt-6', $wrapper);
        self::assertStringContainsString('mb-10', $wrapper);
        self::assertStringContainsString('my-alert', $wrapper);

        $blockClasses = $alert->getBlockClasses();
        self::assertStringContainsString('relative flex items-start', $blockClasses);
        self::assertStringContainsString('rounded-2xl', $blockClasses);
        self::assertStringContainsString('p-8 sm:p-10', $blockClasses);
        self::assertStringNotContainsString('p-4 sm:p-5', $blockClasses);
    }
}
