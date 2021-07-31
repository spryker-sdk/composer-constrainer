<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Zed\ComposerConstrainer\Business\Composer\ComposerLock;

interface ComposerLockReaderInterface
{
    /**
     * @return array
     */
    public function read(): array;

    /**
     * @return \Generated\Shared\Transfer\ComposerConstraintTransfer[]
     */
    public function getConstraints(): array;
}
