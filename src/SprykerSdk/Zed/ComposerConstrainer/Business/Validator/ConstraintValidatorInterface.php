<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Zed\ComposerConstrainer\Business\Validator;

use Generated\Shared\Transfer\ConstraintValidationResultTransfer;

interface ConstraintValidatorInterface
{
    /**
     * @return \Generated\Shared\Transfer\ConstraintValidationResultTransfer
     */
    public function validateConstraints(): ConstraintValidationResultTransfer;
}
