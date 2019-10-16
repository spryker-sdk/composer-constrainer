<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Zed\ComposerConstrainer\Business\Updater;

use Generated\Shared\Transfer\ComposerConstraintCollectionTransfer;
use SprykerSdk\Zed\ComposerConstrainer\Business\Composer\ComposerJson\ComposerJsonReaderInterface;
use SprykerSdk\Zed\ComposerConstrainer\Business\Composer\ComposerJson\ComposerJsonWriterInterface;
use SprykerSdk\Zed\ComposerConstrainer\Business\Validator\ConstraintValidatorInterface;

class ConstraintUpdater implements ConstraintUpdaterInterface
{
    /**
     * @var \SprykerSdk\Zed\ComposerConstrainer\Business\Validator\ConstraintValidatorInterface
     */
    protected $validator;

    /**
     * @var \SprykerSdk\Zed\ComposerConstrainer\Business\Composer\ComposerJson\ComposerJsonReaderInterface
     */
    protected $composerJsonReader;

    /**
     * @var \SprykerSdk\Zed\ComposerConstrainer\Business\Composer\ComposerJson\ComposerJsonWriterInterface
     */
    protected $composerJsonWriter;

    /**
     * @param \SprykerSdk\Zed\ComposerConstrainer\Business\Validator\ConstraintValidatorInterface $validator
     * @param \SprykerSdk\Zed\ComposerConstrainer\Business\Composer\ComposerJson\ComposerJsonReaderInterface $composerJsonReader
     * @param \SprykerSdk\Zed\ComposerConstrainer\Business\Composer\ComposerJson\ComposerJsonWriterInterface $composerJsonWriter
     */
    public function __construct(
        ConstraintValidatorInterface $validator,
        ComposerJsonReaderInterface $composerJsonReader,
        ComposerJsonWriterInterface $composerJsonWriter
    ) {
        $this->validator = $validator;
        $this->composerJsonReader = $composerJsonReader;
        $this->composerJsonWriter = $composerJsonWriter;
    }

    /**
     * @return \Generated\Shared\Transfer\ComposerConstraintCollectionTransfer
     */
    public function updateConstraints(): ComposerConstraintCollectionTransfer
    {
        $constraintConstraintCollectionTransfer = $this->validator->validateConstraints();

        if ($constraintConstraintCollectionTransfer->getComposerConstraints()->count() === 0) {
            return new ComposerConstraintCollectionTransfer();
        }

        $composerJsonArray = $this->composerJsonReader->read();

        foreach ($constraintConstraintCollectionTransfer->getComposerConstraints() as $composerConstraintTransfer) {
            if ($composerConstraintTransfer->getIsDev() === false) {
                $composerJsonArray['require'][$composerConstraintTransfer->getName()] = $composerConstraintTransfer->getExpectedVersion();
            }

            if ($composerConstraintTransfer->getIsDev() === true) {
                $composerJsonArray['require-dev'][$composerConstraintTransfer->getName()] = $composerConstraintTransfer->getExpectedVersion();
            }
        }

        $this->composerJsonWriter->write($composerJsonArray);

        return $constraintConstraintCollectionTransfer;
    }
}
