<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Zed\ComposerConstrainer\Business;

use Spryker\Zed\Kernel\Business\AbstractBusinessFactory;
use SprykerSdk\Zed\ComposerConstrainer\Business\Composer\ComposerJson\ComposerJsonReader;
use SprykerSdk\Zed\ComposerConstrainer\Business\Composer\ComposerJson\ComposerJsonReaderInterface;
use SprykerSdk\Zed\ComposerConstrainer\Business\Composer\ComposerJson\ComposerJsonWriter;
use SprykerSdk\Zed\ComposerConstrainer\Business\Composer\ComposerJson\ComposerJsonWriterInterface;
use SprykerSdk\Zed\ComposerConstrainer\Business\Composer\ComposerLock\ComposerLockReader;
use SprykerSdk\Zed\ComposerConstrainer\Business\Composer\ComposerLock\ComposerLockReaderInterface;
use SprykerSdk\Zed\ComposerConstrainer\Business\Finder\ExtendedModuleFinder\ExtendedModuleFinder;
use SprykerSdk\Zed\ComposerConstrainer\Business\Finder\Finder;
use SprykerSdk\Zed\ComposerConstrainer\Business\Finder\FinderInterface;
use SprykerSdk\Zed\ComposerConstrainer\Business\Finder\VerboseFinder;
use SprykerSdk\Zed\ComposerConstrainer\Business\Updater\ConstraintUpdater;
use SprykerSdk\Zed\ComposerConstrainer\Business\Updater\ConstraintUpdaterInterface;
use SprykerSdk\Zed\ComposerConstrainer\Business\Validator\ConstraintValidator;
use SprykerSdk\Zed\ComposerConstrainer\Business\Validator\ConstraintValidatorInterface;
use SprykerSdk\Zed\ComposerConstrainer\Business\Validator\VerboseConstraintValidator;

/**
 * @method \SprykerSdk\Zed\ComposerConstrainer\ComposerConstrainerConfig getConfig()
 */
class ComposerConstrainerBusinessFactory extends AbstractBusinessFactory
{
    /**
     * @return \SprykerSdk\Zed\ComposerConstrainer\Business\Updater\ConstraintUpdaterInterface
     */
    public function createConstraintUpdater(): ConstraintUpdaterInterface
    {
        return new ConstraintUpdater(
            $this->createConstraintValidator(),
            $this->createComposerJsonReader(),
            $this->createComposerJsonWriter()
        );
    }

    /**
     * @return \SprykerSdk\Zed\ComposerConstrainer\Business\Validator\ConstraintValidatorInterface
     */
    public function createConstraintValidator(): ConstraintValidatorInterface
    {
        return new ConstraintValidator(
            $this->createUsedModuleFinder(),
            $this->createComposerJsonReader(),
            $this->createComposerLockReader()
        );
    }

    /**
     * @return \SprykerSdk\Zed\ComposerConstrainer\Business\Validator\ConstraintValidatorInterface
     */
    public function createVerboseConstraintValidator(): ConstraintValidatorInterface
    {
        return new VerboseConstraintValidator(
            new VerboseFinder($this->getConfig()),
            $this->createComposerJsonReader(),
            $this->createComposerLockReader()
        );
    }

    /**
     * @return \SprykerSdk\Zed\ComposerConstrainer\Business\Finder\FinderInterface
     */
    public function createUsedModuleFinder(): FinderInterface
    {
        return new Finder(
            $this->getFinderStack()
        );
    }

    /**
     * @return \SprykerSdk\Zed\ComposerConstrainer\Business\Finder\FinderInterface[]
     */
    public function getFinderStack(): array
    {
        return [
            $this->createExtendedModuleFinder(),
        ];
    }

    /**
     * @return \SprykerSdk\Zed\ComposerConstrainer\Business\Finder\FinderInterface
     */
    public function createExtendedModuleFinder(): FinderInterface
    {
        return new ExtendedModuleFinder(
            $this->getConfig()
        );
    }

    /**
     * @return \SprykerSdk\Zed\ComposerConstrainer\Business\Composer\ComposerJson\ComposerJsonReaderInterface
     */
    public function createComposerJsonReader(): ComposerJsonReaderInterface
    {
        return new ComposerJsonReader(
            $this->getConfig()
        );
    }

    /**
     * @return \SprykerSdk\Zed\ComposerConstrainer\Business\Composer\ComposerLock\ComposerLockReaderInterface
     */
    public function createComposerLockReader(): ComposerLockReaderInterface
    {
        return new ComposerLockReader(
            $this->getConfig()
        );
    }

    /**
     * @return \SprykerSdk\Zed\ComposerConstrainer\Business\Composer\ComposerJson\ComposerJsonWriterInterface
     */
    public function createComposerJsonWriter(): ComposerJsonWriterInterface
    {
        return new ComposerJsonWriter(
            $this->getConfig()
        );
    }
}
