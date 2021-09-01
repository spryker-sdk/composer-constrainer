<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Zed\ComposerConstrainer\Business\Composer\ComposerLock;

use RuntimeException;
use SprykerSdk\Zed\ComposerConstrainer\ComposerConstrainerConfig;

class ComposerLockReader implements ComposerLockReaderInterface
{
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
     * @throws \RuntimeException
     *
     * @return array
     */
    public function read(): array
    {
        $path = $this->config->getProjectRootPath() . 'composer.lock';
        if (!file_exists($path)) {
            return [];
        }

        $content = file_get_contents($path);
        if ($content === false) {
            throw new RuntimeException('Cannot read content: ' . $path);
        }

        return json_decode($content, true);
    }
}
