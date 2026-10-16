<?php

declare(strict_types=1);

namespace Totoglu\SuluPrelineBlocksBundle\Twig\Components;

use Symfony\UX\TwigComponent\Attribute\AsTwigComponent;

/**
 * Preline UI 5 / Tailwind CSS 4 accessible code block component with copy-to-clipboard plugin.
 */
#[AsTwigComponent('Code', template: '@SuluPrelineBlocks/components/Code.html.twig')]
final class Code extends PrelineComponent
{
    private ?string $uniqueId = null;

    protected function defaults(): array
    {
        return [
            'language' => 'plaintext',
            'show_copy' => true,
            'show_line_numbers' => false,
        ];
    }

    protected function onReset(): void
    {
        $this->uniqueId = null;
    }

    public function shouldRender(): bool
    {
        return $this->has('code');
    }

    public function getId(): string
    {
        if (null === $this->uniqueId) {
            $this->uniqueId = 'hs-code-' . \bin2hex(\random_bytes(4));
        }

        return $this->uniqueId;
    }

    public function getLanguage(): string
    {
        return $this->stringProp('language', 'plaintext');
    }

    public function getLanguageLabel(): string
    {
        return PrelineClassMap::resolve(
            PrelineClassMap::CODE_LANGUAGE_LABELS,
            $this->stringProp('language', 'plaintext', fn: 'lower'),
            'Code',
        );
    }

    /**
     * @return list<string>
     */
    public function getLines(): array
    {
        $raw = $this->string('code', '', trim: false, fn: 'rtrim');

        return '' === $raw ? [''] : \explode("\n", $raw);
    }

    public function getWrapperClasses(string $extraBase = '', string|array|null|false ...$moreParts): string
    {
        return parent::getWrapperClasses(
            'relative w-full rounded-2xl bg-card border border-card-line shadow-xl overflow-hidden my-4',
            $extraBase,
            ...$moreParts,
        );
    }

    public function getContainerClasses(): string
    {
        return $this->getWrapperClasses();
    }

    public function getHeaderClasses(): string
    {
        return 'flex items-center justify-between px-4 py-2.5 bg-card-header border-b border-card-line text-xs font-mono text-muted-foreground-1';
    }

    public function getPreClasses(): string
    {
        return 'p-4 overflow-x-auto text-sm font-mono leading-relaxed text-foreground bg-card';
    }
}
