<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Zed\ComposerConstrainer\Business\Composer\ComposerJson;

use SprykerSdk\Zed\ComposerConstrainer\ComposerConstrainerConfig;

class ComposerJsonWriter implements ComposerJsonWriterInterface
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
     * @param array $composerJsonArray
     *
     * @return bool
     */
    public function write(array $composerJsonArray): bool
    {
        $encodedJson4Spaces = json_encode($composerJsonArray, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
        $encodedJson2Spaces = preg_replace('/^(  +?)\\1(?=[^ ])/m', '$1', $encodedJson4Spaces) . "\n";

        return (bool)file_put_contents($this->config->getProjectRootPath() . 'composer.json', $encodedJson2Spaces);
    }
}
