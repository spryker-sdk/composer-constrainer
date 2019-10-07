<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Zed\ComposerConstrainer\Business;

use Generated\Shared\Transfer\ConstraintUpdateResultTransfer;
use Generated\Shared\Transfer\ConstraintValidationResultTransfer;
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
     * @return \Generated\Shared\Transfer\ConstraintUpdateResultTransfer
     */
    public function updateConstraints(): ConstraintUpdateResultTransfer
    {
        return $this->getFactory()->createConstraintUpdater()->updateConstraints();
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @return \Generated\Shared\Transfer\ConstraintValidationResultTransfer
     */
    public function validateConstraints(): ConstraintValidationResultTransfer
    {
        return $this->getFactory()->createConstraintValidator()->validateConstraints();
    }
}
