<?php

declare(strict_types=1);

namespace Totoglu\SuluPrelineBlocksBundle\Twig\Components;

use Symfony\UX\TwigComponent\Attribute\AsTwigComponent;

/**
 * Preline UI 5 / Tailwind CSS 4 accessible code block component with copy-to-clipboard plugin.
 */
#[AsTwigComponent('Code', template: '@SuluPrelineBlocks/components/Code.html.twig')]
final class Code
{
    public ?string $title = null;
    public string $code = '';
    public string $language = 'plaintext';
    public bool $show_copy = true;
    public bool $show_line_numbers = false;

    /**
     * @var array<string, mixed>
     */
    public array $props = [];

    private ?string $uniqueId = null;

    public function getId(): string
    {
        if ($this->uniqueId === null) {
            $this->uniqueId = 'hs-code-' . \bin2hex(\random_bytes(4));
        }

        return $this->uniqueId;
    }

    public function getLanguageLabel(): string
    {
        return match (\strtolower($this->language)) {
            'php' => 'PHP',
            'javascript', 'js' => 'JavaScript',
            'typescript', 'ts' => 'TypeScript',
            'html' => 'HTML',
            'css' => 'CSS',
            'bash', 'sh', 'shell' => 'Bash',
            'json' => 'JSON',
            'yaml', 'yml' => 'YAML',
            'sql' => 'SQL',
            'python', 'py' => 'Python',
            'markdown', 'md' => 'Markdown',
            default => 'Code',
        };
    }

    /**
     * @return list<string>
     */
    public function getLines(): array
    {
        return \explode("\n", \rtrim($this->code));
    }

    public function getContainerClasses(): string
    {
        return 'relative w-full rounded-2xl bg-neutral-900 border border-neutral-800 shadow-xl overflow-hidden my-4';
    }

    public function getHeaderClasses(): string
    {
        return 'flex items-center justify-between px-4 py-2.5 bg-neutral-950/60 border-b border-neutral-800 text-xs font-mono text-neutral-400';
    }

    public function getPreClasses(): string
    {
        return 'p-4 overflow-x-auto text-sm font-mono leading-relaxed text-neutral-100 bg-neutral-900';
    }
}
