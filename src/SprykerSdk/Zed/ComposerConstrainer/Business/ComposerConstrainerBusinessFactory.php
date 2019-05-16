<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Zed\ComposerConstrainer\Business;

use Spryker\Zed\Kernel\Business\AbstractBusinessFactory;
use SprykerSdk\Zed\ComposerConstrainer\Business\Composer\ComposerJsonReader;
use SprykerSdk\Zed\ComposerConstrainer\Business\Composer\ComposerJsonReaderInterface;
use SprykerSdk\Zed\ComposerConstrainer\Business\Composer\ComposerJsonWriter;
use SprykerSdk\Zed\ComposerConstrainer\Business\Composer\ComposerJsonWriterInterface;
use SprykerSdk\Zed\ComposerConstrainer\Business\Finder\ConfigFinder\ConfigFinder;
use SprykerSdk\Zed\ComposerConstrainer\Business\Finder\DirectoryFinder\DirectoryFinder;
use SprykerSdk\Zed\ComposerConstrainer\Business\Finder\SourceFinder\SourceFinder;
use SprykerSdk\Zed\ComposerConstrainer\Business\Finder\UsedModuleFinder;
use SprykerSdk\Zed\ComposerConstrainer\Business\Finder\UsedModuleFinderInterface;
use SprykerSdk\Zed\ComposerConstrainer\Business\Updater\ConstraintUpdater;
use SprykerSdk\Zed\ComposerConstrainer\Business\Updater\ConstraintUpdaterInterface;
use SprykerSdk\Zed\ComposerConstrainer\Business\Validator\ConstraintValidator;
use SprykerSdk\Zed\ComposerConstrainer\Business\Validator\ConstraintValidatorInterface;
use SprykerSdk\Zed\ComposerConstrainer\Business\Version\ExpectedVersionBuilder;
use SprykerSdk\Zed\ComposerConstrainer\Business\Version\ExpectedVersionBuilderInterface;
use SprykerSdk\Zed\ComposerConstrainer\ComposerConstrainerDependencyProvider;
use SprykerSdk\Zed\ComposerConstrainer\Dependency\Facade\ComposerConstrainerToModuleFinderFacadeInterface;

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
            $this->createExpectedVersionBuilder(),
            $this->createComposerJsonReader(),
            $this->createComposerJsonWriter()
        );
    }

    /**
     * @return \SprykerSdk\Zed\ComposerConstrainer\Business\Version\ExpectedVersionBuilderInterface
     */
    public function createExpectedVersionBuilder(): ExpectedVersionBuilderInterface
    {
        return new ExpectedVersionBuilder();
    }

    /**
     * @return \SprykerSdk\Zed\ComposerConstrainer\Business\Validator\ConstraintValidatorInterface
     */
    public function createConstraintValidator(): ConstraintValidatorInterface
    {
        return new ConstraintValidator(
            $this->createUsedModuleFinder(),
            $this->createComposerJsonReader(),
            $this->createExpectedVersionBuilder()
        );
    }

    /**
     * @return \SprykerSdk\Zed\ComposerConstrainer\Business\Finder\UsedModuleFinderInterface
     */
    public function createUsedModuleFinder(): UsedModuleFinderInterface
    {
        return new UsedModuleFinder(
            $this->getFinderStack()
        );
    }

    /**
     * @return \SprykerSdk\Zed\ComposerConstrainer\Business\Finder\UsedModuleFinderInterface[]
     */
    public function getFinderStack(): array
    {
        return [
            $this->createConfigFinder(),
            $this->createSourceFinder(),
            $this->createDirectoryFinder(),
        ];
    }

    /**
     * @return \SprykerSdk\Zed\ComposerConstrainer\Business\Finder\UsedModuleFinderInterface
     */
    public function createConfigFinder(): UsedModuleFinderInterface
    {
        return new ConfigFinder(
            $this->getConfig()
        );
    }

    /**
     * @return \SprykerSdk\Zed\ComposerConstrainer\Business\Finder\UsedModuleFinderInterface
     */
    public function createSourceFinder(): UsedModuleFinderInterface
    {
        return new SourceFinder(
            $this->getConfig()
        );
    }

    /**
     * @return \SprykerSdk\Zed\ComposerConstrainer\Business\Finder\UsedModuleFinderInterface
     */
    public function createDirectoryFinder(): UsedModuleFinderInterface
    {
        return new DirectoryFinder(
            $this->getConfig(),
            $this->getModuleFinderFacade()
        );
    }

    /**
     * @return \SprykerSdk\Zed\ComposerConstrainer\Business\Composer\ComposerJsonReaderInterface
     */
    public function createComposerJsonReader(): ComposerJsonReaderInterface
    {
        return new ComposerJsonReader(
            $this->getConfig()
        );
    }

    /**
     * @return \SprykerSdk\Zed\ComposerConstrainer\Business\Composer\ComposerJsonWriterInterface
     */
    public function createComposerJsonWriter(): ComposerJsonWriterInterface
    {
        return new ComposerJsonWriter(
            $this->getConfig()
        );
    }

    /**
     * @return \SprykerSdk\Zed\ComposerConstrainer\Dependency\Facade\ComposerConstrainerToModuleFinderFacadeInterface
     */
    public function getModuleFinderFacade(): ComposerConstrainerToModuleFinderFacadeInterface
    {
        return $this->getProvidedDependency(ComposerConstrainerDependencyProvider::FACADE_MODULE_FINDER);
    }
}
