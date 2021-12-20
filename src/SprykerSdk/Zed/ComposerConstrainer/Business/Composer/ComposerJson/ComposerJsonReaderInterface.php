<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Zed\ComposerConstrainer\Business\Composer\ComposerJson;

interface ComposerJsonReaderInterface
{
    /**
     * @return array
     */
    public function read(): array;

    /**
     * @return array<\Generated\Shared\Transfer\ComposerConstraintTransfer>
     */
    public function getConstraints(): array;

    /**
     * @param string $filePath
     *
     * @return array
     */
    public function readFromFilePath(string $filePath): array;
}
