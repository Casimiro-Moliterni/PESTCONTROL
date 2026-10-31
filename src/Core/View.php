<?php
declare(strict_types=1);

namespace App\Core;

/** Rendering di template PHP con layout. */
final class View
{
    private array $shared = [];

    public function __construct(private string $dir)
    {
    }

    public function share(string $key, mixed $value): void
    {
        $this->shared[$key] = $value;
    }

    public function render(string $template, array $data = [], ?string $layout = 'layout'): string
    {
        $vars = $data + $this->shared;
        $content = $this->capture("{$this->dir}/$template.php", $vars);
        if ($layout === null) {
            return $content;
        }
        return $this->capture("{$this->dir}/$layout.php", $vars + ['content' => $content]);
    }

    private function capture(string $file, array $vars): string
    {
        extract($vars, EXTR_SKIP);
        ob_start();
        include $file;
        return (string) ob_get_clean();
    }
}
