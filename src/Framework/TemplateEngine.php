<?php

declare(strict_types=1);

namespace Framework;

class TemplateEngine
{
    public function __construct(private string $basePath)
    {
    }

    public function render(string $template, array $data = []): string
    {
        extract(array_map(fn ($param) => htmlspecialchars($param), $data), EXTR_SKIP);

        ob_start();

        require($this->resolve($template));

        $output = ob_get_contents();

        ob_end_clean();

        return $output;
    }

    public function resolve(string $path): string
    {
        return "{$this->basePath}/{$path}";
    }
}