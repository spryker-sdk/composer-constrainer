<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Zed\ComposerConstrainer\Business\Updater;

use Generated\Shared\Transfer\ConstraintUpdateResultTransfer;
use SprykerSdk\Zed\ComposerConstrainer\Business\Composer\ComposerJsonReaderInterface;
use SprykerSdk\Zed\ComposerConstrainer\Business\Composer\ComposerJsonWriterInterface;
use SprykerSdk\Zed\ComposerConstrainer\Business\Validator\ConstraintValidatorInterface;
use SprykerSdk\Zed\ComposerConstrainer\Business\Version\ExpectedVersionBuilderInterface;

class ConstraintUpdater implements ConstraintUpdaterInterface
{
    /**
     * @var \SprykerSdk\Zed\ComposerConstrainer\Business\Validator\ConstraintValidatorInterface
     */
    protected $validator;

    /**
     * @var \SprykerSdk\Zed\ComposerConstrainer\Business\Version\ExpectedVersionBuilderInterface
     */
    protected $expectedVersionBuilder;

    /**
     * @var \SprykerSdk\Zed\ComposerConstrainer\Business\Composer\ComposerJsonReaderInterface
     */
    protected $composerJsonReader;

    /**
     * @var \SprykerSdk\Zed\ComposerConstrainer\Business\Composer\ComposerJsonWriterInterface
     */
    protected $composerJsonWriter;

    /**
     * @param \SprykerSdk\Zed\ComposerConstrainer\Business\Validator\ConstraintValidatorInterface $validator
     * @param \SprykerSdk\Zed\ComposerConstrainer\Business\Version\ExpectedVersionBuilderInterface $expectedVersionBuilder
     * @param \SprykerSdk\Zed\ComposerConstrainer\Business\Composer\ComposerJsonReaderInterface $composerJsonReader
     * @param \SprykerSdk\Zed\ComposerConstrainer\Business\Composer\ComposerJsonWriterInterface $composerJsonWriter
     */
    public function __construct(
        ConstraintValidatorInterface $validator,
        ExpectedVersionBuilderInterface $expectedVersionBuilder,
        ComposerJsonReaderInterface $composerJsonReader,
        ComposerJsonWriterInterface $composerJsonWriter
    ) {
        $this->validator = $validator;
        $this->expectedVersionBuilder = $expectedVersionBuilder;
        $this->composerJsonReader = $composerJsonReader;
        $this->composerJsonWriter = $composerJsonWriter;
    }

    /**
     * @return \Generated\Shared\Transfer\ConstraintUpdateResultTransfer
     */
    public function updateConstraints(): ConstraintUpdateResultTransfer
    {
        $constraintUpdateResultTransfer = new ConstraintUpdateResultTransfer();
        $constraintValidateResultTransfer = $this->validator->validateConstraints();

        if ($constraintValidateResultTransfer->getInvalidConstraints()->count() === 0) {
            return $constraintUpdateResultTransfer;
        }

        $composerJsonArray = $this->composerJsonReader->read();

        foreach ($constraintValidateResultTransfer->getInvalidConstraints() as $invalidConstraintTransfer) {
            $expectedVersion = $this->expectedVersionBuilder->buildExpectedVersion($invalidConstraintTransfer->getVersion());

            if (isset($composerJsonArray['require']) && isset($composerJsonArray['require'][$invalidConstraintTransfer->getName()])) {
                $composerJsonArray['require'][$invalidConstraintTransfer->getName()] = $expectedVersion;
            }

            if (isset($composerJsonArray['require-dev']) && isset($composerJsonArray['require-dev'][$invalidConstraintTransfer->getName()])) {
                $composerJsonArray['require-dev'][$invalidConstraintTransfer->getName()] = $expectedVersion;
            }
        }

        $this->composerJsonWriter->write($composerJsonArray);

        $constraintUpdateResultTransfer->setUpdatedConstraints($constraintValidateResultTransfer->getInvalidConstraints());

        return $constraintUpdateResultTransfer;
    }
}
