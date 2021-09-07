<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Zed\ComposerConstrainer\Business;

use Generated\Shared\Transfer\ComposerConstraintCollectionTransfer;

interface ComposerConstrainerFacadeInterface
{
    /**
     * Specification:
     * - Updates core modules constraints.
     *
     * @api
     *
     * @param bool $isStrict
     *
     * @return \Generated\Shared\Transfer\ComposerConstraintCollectionTransfer
     */
    public function updateConstraints(bool $isStrict = false): ComposerConstraintCollectionTransfer;

    /**
     * Specification:
     * - Validates core modules constraints.
     *
     * @api
     *
     * @param bool $isStrict
     *
     * @return \Generated\Shared\Transfer\ComposerConstraintCollectionTransfer
     */
    public function validateConstraints(bool $isStrict = false): ComposerConstraintCollectionTransfer;

    /**
     * Specification:
     * - Validates foreign modules constraints.
     *
     * @api
     *
     * @return \Generated\Shared\Transfer\ComposerConstraintCollectionTransfer
     */
    public function validateForeignConstraints(): ComposerConstraintCollectionTransfer;

    /**
     * Specification:
     * - Updates foreign modules constraints.
     *
     * @api
     *
     * @return \Generated\Shared\Transfer\ComposerConstraintCollectionTransfer
     */
    public function updateForeignConstraints(): ComposerConstraintCollectionTransfer;
}
