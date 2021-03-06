<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Zed\ComposerConstrainer\Business\Composer\ComposerJson;

use SprykerSdk\Zed\ComposerConstrainer\ComposerConstrainerConfig;

class ComposerJsonReader implements ComposerJsonReaderInterface
{
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
     * @return array
     */
    public function readFromFilePath(string $filePath): array
    {
        $composerJsonFileName = $filePath . static::COMPOSER_JSON_FILENAME;
        if (!file_exists($composerJsonFileName)) {
            return [];
        }

        return json_decode(file_get_contents($composerJsonFileName), true);
    }
}
