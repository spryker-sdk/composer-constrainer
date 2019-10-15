<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Zed\ComposerConstrainer\Business\Validator;

use Generated\Shared\Transfer\ComposerConstraintCollectionTransfer;
use Generated\Shared\Transfer\ComposerConstraintTransfer;
use Generated\Shared\Transfer\ConstraintMessageTransfer;
use Generated\Shared\Transfer\UsedModuleTransfer;
use SprykerSdk\Zed\ComposerConstrainer\Business\Composer\ComposerJson\ComposerJsonReaderInterface;
use SprykerSdk\Zed\ComposerConstrainer\Business\Composer\ComposerLock\ComposerLockReaderInterface;
use SprykerSdk\Zed\ComposerConstrainer\Business\Finder\FinderInterface;
use SprykerSdk\Zed\ComposerConstrainer\Business\Version\ExpectedVersionBuilderInterface;
use Zend\Filter\FilterChain;
use Zend\Filter\StringToLower;
use Zend\Filter\Word\CamelCaseToDash;

class ConstraintValidator implements ConstraintValidatorInterface
{
    /**
     * @var \SprykerSdk\Zed\ComposerConstrainer\Business\Finder\FinderInterface
     */
    protected $usedModuleFinder;

    /**
     * @var \SprykerSdk\Zed\ComposerConstrainer\Business\Composer\ComposerJson\ComposerJsonReaderInterface
     */
    protected $composerJsonReader;

    /**
     * @var \SprykerSdk\Zed\ComposerConstrainer\Business\Composer\ComposerLock\ComposerLockReaderInterface
     */
    protected $composerLockReader;

    /**
     * @var \SprykerSdk\Zed\ComposerConstrainer\Business\Version\ExpectedVersionBuilderInterface
     */
    protected $expectedVersionBuilder;

    /**
     * @var \Zend\Filter\FilterChain|null
     */
    protected $filterChain;

    /**
     * @param \SprykerSdk\Zed\ComposerConstrainer\Business\Finder\FinderInterface $usedModuleFinder
     * @param \SprykerSdk\Zed\ComposerConstrainer\Business\Composer\ComposerJson\ComposerJsonReaderInterface $composerJsonReader
     * @param \SprykerSdk\Zed\ComposerConstrainer\Business\Composer\ComposerLock\ComposerLockReaderInterface $composerLockReader
     * @param \SprykerSdk\Zed\ComposerConstrainer\Business\Version\ExpectedVersionBuilderInterface $expectedVersionBuilder
     */
    public function __construct(
        FinderInterface $usedModuleFinder,
        ComposerJsonReaderInterface $composerJsonReader,
        ComposerLockReaderInterface $composerLockReader,
        ExpectedVersionBuilderInterface $expectedVersionBuilder
    ) {
        $this->usedModuleFinder = $usedModuleFinder;
        $this->composerJsonReader = $composerJsonReader;
        $this->composerLockReader = $composerLockReader;
        $this->expectedVersionBuilder = $expectedVersionBuilder;
    }

    /**
     * @return \Generated\Shared\Transfer\ComposerConstraintCollectionTransfer
     */
    public function validateConstraints(): ComposerConstraintCollectionTransfer
    {
        $usedModules = $this->getUsedModulesAsComposerName();
        $composerConstraints = $this->getComposerConstraints();

        $composerConstraintCollectionTransfer = new ComposerConstraintCollectionTransfer();

        if (count($usedModules) === 0) {
            return $composerConstraintCollectionTransfer;
        }

        foreach ($usedModules as $composerName) {
            if (!$this->isVersionValid($composerConstraints[$composerName])) {
                $composerConstraintCollectionTransfer = $this->addInvalidConstraint($composerName, $composerConstraints, $composerConstraintCollectionTransfer);
            }
        }

        return $composerConstraintCollectionTransfer;
    }

    /**
     * @param string $composerName
     * @param \Generated\Shared\Transfer\ComposerConstraintTransfer[] $composerConstraints
     * @param \Generated\Shared\Transfer\ComposerConstraintCollectionTransfer $composerConstraintCollectionTransfer
     *
     * @return \Generated\Shared\Transfer\ComposerConstraintCollectionTransfer
     */
    protected function addInvalidConstraint(
        string $composerName,
        array $composerConstraints,
        ComposerConstraintCollectionTransfer $composerConstraintCollectionTransfer
    ): ComposerConstraintCollectionTransfer {
        $composerConstraintTransfer = $composerConstraints[$composerName];

        $expectedVersion = $this->expectedVersionBuilder->buildExpectedVersion($composerConstraintTransfer->getVersion());
        $composerConstraintTransfer->setExpectedVersion($expectedVersion);

        $constraintMessageTransfer = new ConstraintMessageTransfer();
        $constraintMessageTransfer->setMessage(sprintf(
            '"%s" expected in version "%s" to be locked down in your composer.json',
            $composerName,
            $expectedVersion
        ));

        $composerConstraintTransfer->addMessage($constraintMessageTransfer);
        $composerConstraintCollectionTransfer->addComposerConstraint($composerConstraintTransfer);

        return $composerConstraintCollectionTransfer;
    }

    /**
     * @return string[]
     */
    protected function getUsedModulesAsComposerName(): array
    {
        $usedModuleCollectionTransfer = $this->usedModuleFinder->find();
        $usedModules = [];

        foreach ($usedModuleCollectionTransfer->getUsedModules() as $usedModuleTransfer) {
            $composerName = $this->buildComposerName($usedModuleTransfer);
            $usedModules[$composerName] = $composerName;
        }

        return $usedModules;
    }

    /**
     * @return \Generated\Shared\Transfer\ComposerConstraintTransfer[]
     */
    protected function getComposerConstraints(): array
    {
        $composerConstraintCollectionTransfer = $this->getComposerConstraintCollectionTransfer();
        $composerConstraints = [];

        foreach ($composerConstraintCollectionTransfer->getComposerConstraints() as $composerConstraintTransfer) {
            $composerConstraints[$composerConstraintTransfer->getName()] = $composerConstraintTransfer;
        }

        return $composerConstraints;
    }

    /**
     * @return \Generated\Shared\Transfer\ComposerConstraintCollectionTransfer
     */
    protected function getComposerConstraintCollectionTransfer(): ComposerConstraintCollectionTransfer
    {
        $composerConstraintCollectionTransfer = new ComposerConstraintCollectionTransfer();

        $composerConstraintCollectionTransfer = $this->addConstraintsFromComposerLock($composerConstraintCollectionTransfer);
        $composerConstraintCollectionTransfer = $this->addConstraintsFromComposerJson($composerConstraintCollectionTransfer);

        return $composerConstraintCollectionTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\ComposerConstraintCollectionTransfer $composerConstraintCollectionTransfer
     *
     * @return \Generated\Shared\Transfer\ComposerConstraintCollectionTransfer
     */
    protected function addConstraintsFromComposerLock(ComposerConstraintCollectionTransfer $composerConstraintCollectionTransfer): ComposerConstraintCollectionTransfer
    {
        $composerArray = $this->composerLockReader->read();

        foreach (['packages', 'packages-dev'] as $type) {
            if (!isset($composerArray[$type])) {
                continue;
            }

            foreach ($composerArray[$type] as $package) {
                $composerConstraintTransfer = new ComposerConstraintTransfer();
                $composerConstraintTransfer
                    ->setName($package['name'])
                    ->setVersion($package['version'])
                    ->setIsDev($type === 'packages-dev');

                $composerConstraintCollectionTransfer->addComposerConstraint($composerConstraintTransfer);
            }
        }

        return $composerConstraintCollectionTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\ComposerConstraintCollectionTransfer $composerConstraintCollectionTransfer
     *
     * @return \Generated\Shared\Transfer\ComposerConstraintCollectionTransfer
     */
    protected function addConstraintsFromComposerJson(ComposerConstraintCollectionTransfer $composerConstraintCollectionTransfer): ComposerConstraintCollectionTransfer
    {
        $composerArray = $this->composerJsonReader->read();

        foreach (['require', 'require-dev'] as $type) {
            if (!isset($composerArray[$type])) {
                continue;
            }

            foreach ($composerArray[$type] as $name => $version) {
                $composerConstraintTransfer = new ComposerConstraintTransfer();
                $composerConstraintTransfer
                    ->setName($name)
                    ->setVersion($version)
                    ->setIsDev($type === 'require-dev');

                $composerConstraintCollectionTransfer->addComposerConstraint($composerConstraintTransfer);
            }
        }

        return $composerConstraintCollectionTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\UsedModuleTransfer $usedModuleTransfer
     *
     * @return string
     */
    protected function buildComposerName(UsedModuleTransfer $usedModuleTransfer): string
    {
        return sprintf(
            '%s/%s',
            $this->getFilterChain()->filter($usedModuleTransfer->getOrganization()),
            $this->getFilterChain()->filter($usedModuleTransfer->getModule())
        );
    }

    /**
     * @return \Zend\Filter\FilterChain
     */
    protected function getFilterChain(): FilterChain
    {
        if ($this->filterChain === null) {
            $this->filterChain = new FilterChain();
            $this->filterChain
                ->attach(new CamelCaseToDash())
                ->attach(new StringToLower());
        }

        return $this->filterChain;
    }

    /**
     * @param \Generated\Shared\Transfer\ComposerConstraintTransfer $composerConstraintTransfer
     *
     * @return bool
     */
    protected function isVersionValid(ComposerConstraintTransfer $composerConstraintTransfer): bool
    {
        $currentVersions = explode('|', $composerConstraintTransfer->getVersion());
        foreach ($currentVersions as $currentVersion) {
            $currentVersion = trim($currentVersion);

            if ($currentVersion[0] !== '~' && $currentVersion[1] !== '0') {
                return false;
            }
        }

        return true;
    }
}
