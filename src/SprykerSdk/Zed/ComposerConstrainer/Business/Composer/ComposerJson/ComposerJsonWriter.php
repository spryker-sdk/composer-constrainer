<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Zed\ComposerConstrainer\Business\Composer\ComposerJson;

use Ergebnis\Json\Printer\Printer;
use RuntimeException;
use SprykerSdk\Zed\ComposerConstrainer\ComposerConstrainerConfig;

class ComposerJsonWriter implements ComposerJsonWriterInterface
{
    protected const INDENTATION_DEFAULT = 4;

    /**
     * @var \SprykerSdk\Zed\ComposerConstrainer\ComposerConstrainerConfig
     */
    protected $config;

    /**
     * @param \SprykerSdk\Zed\ComposerConstrainer\ComposerConstrainerConfig $config
     */
    public function __construct(ComposerConstrainerConfig $config)
    {
        $this->config = $config;
    }

    /**
     * @param array $composerJsonArray
     *
     * @return bool
     */
    public function write(array $composerJsonArray): bool
    {
        $encodedJson = json_encode($composerJsonArray, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES) . PHP_EOL;

        $indentation = static::INDENTATION_DEFAULT;
        $composerJsonFileName = $this->config->getProjectRootPath() . 'composer.json';
        if (file_exists($composerJsonFileName)) {
            $indentation = $this->autoDetectIndentation($composerJsonFileName);
        }
        if ($indentation !== static::INDENTATION_DEFAULT) {
            $encodedJson = $this->adjustIndentation($encodedJson, $indentation);
        }

        return (bool)file_put_contents($composerJsonFileName, $encodedJson);
    }

    /**
     * @param string $composerJsonFileName
     *
     * @throws \RuntimeException
     *
     * @return int
     */
    protected function autoDetectIndentation(string $composerJsonFileName): int
    {
        $content = file_get_contents($composerJsonFileName);
        if ($content === false) {
            throw new RuntimeException('Cannot read file ' . $composerJsonFileName);
        }

        preg_match('/^(.+)(".+":)/m', $content, $matches);
        if (!$matches) {
            return static::INDENTATION_DEFAULT;
        }

        return strlen($matches[1]);
    }

    /**
     * @param string $encodedJson
     * @param int $indentation
     *
     * @throws \RuntimeException
     *
     * @return string
     */
    protected function adjustIndentation(string $encodedJson, int $indentation): string
    {
        if (!class_exists(Printer::class)) {
            throw new RuntimeException(
                sprintf('Non default 4 space indentation requires package `%s` installed.', 'ergebnis/json-printer')
            );
        }

        return (new Printer())->print($encodedJson, str_repeat(' ', $indentation)) . PHP_EOL;
    }
}
