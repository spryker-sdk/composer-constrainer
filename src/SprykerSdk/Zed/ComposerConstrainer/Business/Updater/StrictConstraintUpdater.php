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

class StrictConstraintUpdater implements ConstraintUpdaterInterface
{
    /**
     * @var \SprykerSdk\Zed\ComposerConstrainer\Business\Validator\ConstraintValidatorInterface
     */
    protected $strictConstraintValidator;

    /**
     * @var \SprykerSdk\Zed\ComposerConstrainer\Business\Composer\ComposerJson\ComposerJsonReaderInterface
     */
    protected $composerJsonReader;

    /**
     * @var \SprykerSdk\Zed\ComposerConstrainer\Business\Composer\ComposerJson\ComposerJsonWriterInterface
     */
    protected $composerJsonWriter;

    /**
     * @param \SprykerSdk\Zed\ComposerConstrainer\Business\Validator\ConstraintValidatorInterface $strictConstraintValidator
     * @param \SprykerSdk\Zed\ComposerConstrainer\Business\Composer\ComposerJson\ComposerJsonReaderInterface $composerJsonReader
     * @param \SprykerSdk\Zed\ComposerConstrainer\Business\Composer\ComposerJson\ComposerJsonWriterInterface $composerJsonWriter
     */
    public function __construct(
        ConstraintValidatorInterface $strictConstraintValidator,
        ComposerJsonReaderInterface $composerJsonReader,
        ComposerJsonWriterInterface $composerJsonWriter
    ) {
        $this->strictConstraintValidator = $strictConstraintValidator;
        $this->composerJsonReader = $composerJsonReader;
        $this->composerJsonWriter = $composerJsonWriter;
    }

    /**
     * @return \Generated\Shared\Transfer\ComposerConstraintCollectionTransfer
     */
    public function updateConstraints(): ComposerConstraintCollectionTransfer
    {
        $constraintCollectionTransfer = $this->strictConstraintValidator->validateConstraints();

        if ($constraintCollectionTransfer->getComposerConstraints()->count() === 0) {
            return new ComposerConstraintCollectionTransfer();
        }

        $composerJsonArray = $this->composerJsonReader->read();
        foreach ($constraintCollectionTransfer->getComposerConstraints() as $composerConstraintTransfer) {
            $moduleInfoTransfer = $composerConstraintTransfer->getModuleInfo();
            $requireType = $moduleInfoTransfer->getIsDev() ? 'require-dev' : 'require';

            $composerJsonArray[$requireType][$composerConstraintTransfer->getName()] = $moduleInfoTransfer->getExpectedConstraintLock() . $moduleInfoTransfer->getExpectedVersion();
        }

        $this->composerJsonWriter->write($composerJsonArray);

        return $constraintCollectionTransfer;
    }
}
