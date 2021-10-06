<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Zed\ComposerConstrainer\Business\Composer\ComposerJson;

use Generated\Shared\Transfer\ComposerConstraintTransfer;
use RuntimeException;
use SprykerSdk\Zed\ComposerConstrainer\ComposerConstrainerConfig;

class ComposerJsonReader implements ComposerJsonReaderInterface
{
    /**
     * @var string
     */
    protected const COMPOSER_JSON_FILENAME = 'composer.json';

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
     * @return array
     */
    public function read(): array
    {
        return $this->readFromFilePath($this->config->getProjectRootPath());
    }

    /**
     * @param string $filePath
     *
     * @throws \RuntimeException
     *
     * @return array
     */
    public function readFromFilePath(string $filePath): array
    {
        $path = $filePath . static::COMPOSER_JSON_FILENAME;
        if (!file_exists($path)) {
            return [];
        }

        $content = file_get_contents($path);
        if ($content === false) {
            throw new RuntimeException('Cannot read content: ' . $path);
        }

        return json_decode($content, true);
    }

    /**
     * @return \Generated\Shared\Transfer\ComposerConstraintTransfer[]
     */
    public function getConstraints(): array
    {
        $composerConstraints = [];
        $composerArray = $this->read();

        foreach (['require', 'require-dev'] as $type) {
            if (!isset($composerArray[$type])) {
                continue;
            }

            foreach ($composerArray[$type] as $name => $version) {
                $composerConstraintTransfer = new ComposerConstraintTransfer();
                $composerConstraintTransfer
                    ->setName($name)
                    ->setVersion($version)
                    ->setIsDev($type === 'require-dev');

                $composerConstraints[$name] = $composerConstraintTransfer;
            }
        }

        ksort($composerConstraints);

        return $composerConstraints;
    }
}
