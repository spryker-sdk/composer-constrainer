<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Zed\ComposerConstrainer\Business\Updater;

use Generated\Shared\Transfer\ComposerConstraintCollectionTransfer;

interface ConstraintUpdaterInterface
{
    /**
     * @return \Generated\Shared\Transfer\ComposerConstraintCollectionTransfer
     */
    public function updateConstraints(): ComposerConstraintCollectionTransfer;
}
