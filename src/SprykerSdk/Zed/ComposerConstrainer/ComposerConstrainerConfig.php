<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Zed\ComposerConstrainer;

use Spryker\Zed\Kernel\AbstractBundleConfig;
use SprykerSdk\Shared\ComposerConstrainer\ComposerConstrainerConstants;

class ComposerConstrainerConfig extends AbstractBundleConfig
{
    /**
     * @return string
     */
    public function getSourceDirectory(): string
    {
        return $this->getProjectRootPath() . 'src/';
    }

    /**
     * @codeCoverageIgnore Makes use of constant we only have in project context.
     *
     * @return string
     */
    public function getProjectRootPath(): string
    {
        return rtrim(APPLICATION_ROOT_DIR, '/') . '/';
    }

    /**
     * @return string[]
     */
    public function getCoreNamespaces(): array
    {
        return $this->get(ComposerConstrainerConstants::CORE_NAMESPACES);
    }
}
