<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Zed\ComposerConstrainer\Business\Version;

interface ExpectedVersionBuilderInterface
{
    /**
     * @param string $currentVersion
     *
     * @return string
     */
    public function buildExpectedVersion(string $currentVersion): string;
}
