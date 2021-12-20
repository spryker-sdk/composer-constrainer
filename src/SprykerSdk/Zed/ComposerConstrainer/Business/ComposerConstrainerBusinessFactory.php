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
use SprykerSdk\Zed\ComposerConstrainer\Business\Finder\StrictFinder;
use SprykerSdk\Zed\ComposerConstrainer\Business\Finder\UsedForeignModuleFinder\UsedForeignModuleFinder;
use SprykerSdk\Zed\ComposerConstrainer\Business\Updater\ConstraintUpdater;
use SprykerSdk\Zed\ComposerConstrainer\Business\Updater\ConstraintUpdaterInterface;
use SprykerSdk\Zed\ComposerConstrainer\Business\Updater\StrictConstraintUpdater;
use SprykerSdk\Zed\ComposerConstrainer\Business\Validator\ConstraintValidator;
use SprykerSdk\Zed\ComposerConstrainer\Business\Validator\ConstraintValidatorInterface;
use SprykerSdk\Zed\ComposerConstrainer\Business\Validator\StrictConstraintValidator;

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
            $this->createComposerJsonWriter(),
        );
    }

    /**
     * @return \SprykerSdk\Zed\ComposerConstrainer\Business\Updater\ConstraintUpdaterInterface
     */
    public function createStrictConstraintUpdater(): ConstraintUpdaterInterface
    {
        return new StrictConstraintUpdater(
            $this->createStrictConstraintValidator(),
            $this->createComposerJsonReader(),
            $this->createComposerJsonWriter(),
        );
    }

    /**
     * @return \SprykerSdk\Zed\ComposerConstrainer\Business\Updater\ConstraintUpdaterInterface
     */
    public function createForeignConstraintUpdater(): ConstraintUpdaterInterface
    {
        return new ConstraintUpdater(
            $this->createForeignConstraintValidator(),
            $this->createComposerJsonReader(),
            $this->createComposerJsonWriter(),
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
            $this->createComposerLockReader(),
        );
    }

    /**
     * @return \SprykerSdk\Zed\ComposerConstrainer\Business\Validator\ConstraintValidatorInterface
     */
    public function createStrictConstraintValidator(): ConstraintValidatorInterface
    {
        return new StrictConstraintValidator(
            $this->getConfig(),
            $this->createStrictFinder(),
            $this->createComposerJsonReader(),
            $this->createComposerLockReader(),
        );
    }

    /**
     * @return \SprykerSdk\Zed\ComposerConstrainer\Business\Finder\FinderInterface
     */
    public function createStrictFinder(): FinderInterface
    {
        return new StrictFinder($this->getConfig());
    }

    /**
     * @return \SprykerSdk\Zed\ComposerConstrainer\Business\Validator\ConstraintValidatorInterface
     */
    public function createForeignConstraintValidator(): ConstraintValidatorInterface
    {
        return new ConstraintValidator(
            $this->createForeignModuleFinder(),
            $this->createComposerJsonReader(),
            $this->createComposerLockReader(),
        );
    }

    /**
     * @return \SprykerSdk\Zed\ComposerConstrainer\Business\Finder\FinderInterface
     */
    public function createUsedModuleFinder(): FinderInterface
    {
        return new Finder(
            $this->getFinderStack(),
        );
    }

    /**
     * @return \SprykerSdk\Zed\ComposerConstrainer\Business\Finder\FinderInterface
     */
    public function createForeignModuleFinder(): FinderInterface
    {
        return new Finder(
            $this->getUsedForeignFinderStack(),
        );
    }

    /**
     * @return array<\SprykerSdk\Zed\ComposerConstrainer\Business\Finder\FinderInterface>
     */
    public function getFinderStack(): array
    {
        return [
            $this->createExtendedModuleFinder(),
        ];
    }

    /**
     * @return array<\SprykerSdk\Zed\ComposerConstrainer\Business\Finder\FinderInterface>
     */
    public function getUsedForeignFinderStack(): array
    {
        return [
            $this->createUsedForeignModuleFinder(),
        ];
    }

    /**
     * @return \SprykerSdk\Zed\ComposerConstrainer\Business\Finder\FinderInterface
     */
    public function createExtendedModuleFinder(): FinderInterface
    {
        return new ExtendedModuleFinder(
            $this->getConfig(),
        );
    }

    /**
     * @return \SprykerSdk\Zed\ComposerConstrainer\Business\Finder\FinderInterface
     */
    public function createUsedForeignModuleFinder(): FinderInterface
    {
        return new UsedForeignModuleFinder(
            $this->getConfig(),
            $this->createComposerJsonReader(),
        );
    }

    /**
     * @return \SprykerSdk\Zed\ComposerConstrainer\Business\Composer\ComposerJson\ComposerJsonReaderInterface
     */
    public function createComposerJsonReader(): ComposerJsonReaderInterface
    {
        return new ComposerJsonReader(
            $this->getConfig(),
        );
    }

    /**
     * @return \SprykerSdk\Zed\ComposerConstrainer\Business\Composer\ComposerLock\ComposerLockReaderInterface
     */
    public function createComposerLockReader(): ComposerLockReaderInterface
    {
        return new ComposerLockReader(
            $this->getConfig(),
        );
    }

    /**
     * @return \SprykerSdk\Zed\ComposerConstrainer\Business\Composer\ComposerJson\ComposerJsonWriterInterface
     */
    public function createComposerJsonWriter(): ComposerJsonWriterInterface
    {
        return new ComposerJsonWriter(
            $this->getConfig(),
        );
    }
}
