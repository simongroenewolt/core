<?php

declare(strict_types=1);

namespace Bolt\Configuration\Parser;

use Tightenco\Collect\Support\Collection;

class ThemeParser extends BaseParser
{
    /** @var string */
    private $path;

    public function __construct(string $boltConfigDir, string $path, string $filename = 'theme.yaml')
    {
        $this->path = $path;

        parent::__construct($boltConfigDir, $filename);
    }

    /**
     * Read and parse the theme.yml configuration file.
     */
    public function parse(): Collection
    {
        $theme = $this->parseConfigYaml($this->path . '/theme.yaml', true);

        if (! isset($theme['template_directory'])) {
            // the theme itself
            $theme['template_directory'] = './';
        }

        return new Collection($theme);
    }
}
