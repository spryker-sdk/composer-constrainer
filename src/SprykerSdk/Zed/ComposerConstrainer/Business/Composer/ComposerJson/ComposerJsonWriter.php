<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Zed\ComposerConstrainer\Business\Composer\ComposerJson;

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
        $encodedJson = json_encode($composerJsonArray, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);

        $indentation = static::INDENTATION_DEFAULT;
        $composerJsonFileName = $this->config->getProjectRootPath() . 'composer.json';
        if (file_exists($composerJsonFileName)) {
            $indentation = $this->autoDetectIndentation($composerJsonFileName);
        }
        if ($indentation !== static::INDENTATION_DEFAULT) {
            $encodedJson = preg_replace('/^(    +?)\\1(?=[^' . str_repeat(' ', $indentation) . '])/m', '$1', $encodedJson) . "\n";
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

        preg_match('/^(.+)"name":/', $content, $matches);
        if (!$matches) {
            return static::INDENTATION_DEFAULT;
        }

        return strlen($matches[1]);
    }
}
