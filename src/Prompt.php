<?php

namespace WpAi\EleLLM;

use Jenssegers\Blade\Blade;

/**
 * A Blade templating engine for prompt files and strings.
 */
class Prompt
{
    private Blade $blade;

    public function __construct(
        private string $fileDir,
        private string $cacheDir
    ) {
        $this->blade = new Blade($fileDir, $cacheDir);
    }

    /**
     * Render a Blade template from a file.
     *
     * @param  string  $templateName  The name of the Blade template file (without extension).
     * @param  array  $data  Data to pass to the template.
     * @return string The rendered template content.
     */
    public function file(string $templateName, array $data = []): string
    {
        return $this->blade->render($templateName, $data);
    }

    /**
     * @todo: not working yet.
     * Render a Blade template from a string.
     *
     * @param  string  $templateString  The Blade template as a string.
     * @param  array  $data  Data to pass to the template.
     * @return string The rendered template content.
     */
    public function render(string $templateString, array $data = []): string
    {
        return $this->blade->compiler()->render($templateString, $data);
    }
}
