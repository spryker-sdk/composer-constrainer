<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Zed\ComposerConstrainer\Business;

use Generated\Shared\Transfer\ComposerConstraintCollectionTransfer;
use Spryker\Zed\Kernel\Business\AbstractFacade;

/**
 * @method \SprykerSdk\Zed\ComposerConstrainer\Business\ComposerConstrainerBusinessFactory getFactory()
 */
class ComposerConstrainerFacade extends AbstractFacade implements ComposerConstrainerFacadeInterface
{
    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @return \Generated\Shared\Transfer\ComposerConstraintCollectionTransfer
     */
    public function updateConstraints(): ComposerConstraintCollectionTransfer
    {
        return $this->getFactory()->createConstraintUpdater()->updateConstraints();
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param bool $isStrict
     *
     * @return ComposerConstraintCollectionTransfer
     */
    public function validateConstraints($isStrict = false): ComposerConstraintCollectionTransfer
    {
        // Deprecated: Will be replaced by strict validation only
        if ($isStrict === false) {
            return $this->getFactory()->createConstraintValidator()->validateConstraints();
        }

        return $this->getFactory()->createStrictConstraintValidator()->validateConstraints();
    }
}
