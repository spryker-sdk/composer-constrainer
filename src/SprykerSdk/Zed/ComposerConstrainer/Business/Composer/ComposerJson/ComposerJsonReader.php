<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Zed\ComposerConstrainer\Business\Composer\ComposerJson;

use SprykerSdk\Zed\ComposerConstrainer\ComposerConstrainerConfig;

class ComposerJsonReader implements ComposerJsonReaderInterface
{
    public const COMPOSER_JSON_FILENAME = 'composer.json';

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
        return $this->readFromPath($this->config->getProjectRootPath());
    }

    /**
     * @param string $path
     *
     * @return array
     */
    public function readFromFilePath(string $filePath): array
    {
        return json_decode(file_get_contents($path . static::COMPOSER_JSON_FILENAME), true);
    }
}
