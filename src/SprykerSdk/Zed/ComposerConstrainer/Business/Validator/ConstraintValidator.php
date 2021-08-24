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
use Laminas\Filter\FilterChain;
use Laminas\Filter\StringToLower;
use Laminas\Filter\Word\CamelCaseToDash;
use SprykerSdk\Zed\ComposerConstrainer\Business\Composer\ComposerJson\ComposerJsonReaderInterface;
use SprykerSdk\Zed\ComposerConstrainer\Business\Composer\ComposerLock\ComposerLockReaderInterface;
use SprykerSdk\Zed\ComposerConstrainer\Business\Finder\FinderInterface;

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
     * @var \Laminas\Filter\FilterChain|null
     */
    protected $filterChain;

    /**
     * @param \SprykerSdk\Zed\ComposerConstrainer\Business\Finder\FinderInterface $usedModuleFinder
     * @param \SprykerSdk\Zed\ComposerConstrainer\Business\Composer\ComposerJson\ComposerJsonReaderInterface $composerJsonReader
     * @param \SprykerSdk\Zed\ComposerConstrainer\Business\Composer\ComposerLock\ComposerLockReaderInterface $composerLockReader
     */
    public function __construct(
        FinderInterface $usedModuleFinder,
        ComposerJsonReaderInterface $composerJsonReader,
        ComposerLockReaderInterface $composerLockReader
    ) {
        $this->usedModuleFinder = $usedModuleFinder;
        $this->composerJsonReader = $composerJsonReader;
        $this->composerLockReader = $composerLockReader;
    }

    /**
     * @return \Generated\Shared\Transfer\ComposerConstraintCollectionTransfer
     */
    public function validateConstraints(): ComposerConstraintCollectionTransfer
    {
        $usedModules = $this->getUsedModulesAsComposerName();

        $composerConstraintCollectionTransfer = new ComposerConstraintCollectionTransfer();

        if (count($usedModules) === 0) {
            return $composerConstraintCollectionTransfer;
        }

        $composerLockConstraints = $this->getComposerLockConstraints();
        $composerJsonConstraints = $this->getComposerJsonConstraints();

        foreach ($usedModules as $composerName) {
            if (!isset($composerJsonConstraints[$composerName])) {
                $composerConstraintCollectionTransfer = $this->addInvalidConstraint($composerName, $composerLockConstraints, $composerConstraintCollectionTransfer);

                continue;
            }

            if (!$this->isVersionValid($composerLockConstraints[$composerName], $composerJsonConstraints[$composerName])) {
                $composerConstraintCollectionTransfer = $this->addInvalidConstraint($composerName, $composerLockConstraints, $composerConstraintCollectionTransfer);
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

        $expectedVersion = sprintf('~%s', $composerConstraintTransfer->getVersion());
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

        ksort($usedModules);

        return $usedModules;
    }

    /**
     * @return \Generated\Shared\Transfer\ComposerConstraintTransfer[]
     */
    protected function getComposerLockConstraints(): array
    {
        $composerConstraints = [];
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

                $composerConstraints[$package['name']] = $composerConstraintTransfer;
            }
        }

        ksort($composerConstraints);

        return $composerConstraints;
    }

    /**
     * @return \Generated\Shared\Transfer\ComposerConstraintTransfer[]
     */
    protected function getComposerJsonConstraints(): array
    {
        $composerConstraints = [];
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

                $composerConstraints[$name] = $composerConstraintTransfer;
            }
        }

        ksort($composerConstraints);

        return $composerConstraints;
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
     * @return \Laminas\Filter\FilterChain
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
     * @param \Generated\Shared\Transfer\ComposerConstraintTransfer $composerLockConstraintTransfer
     * @param \Generated\Shared\Transfer\ComposerConstraintTransfer $composerJsonConstraintTransfer
     *
     * @return bool
     */
    protected function isVersionValid(
        ComposerConstraintTransfer $composerLockConstraintTransfer,
        ComposerConstraintTransfer $composerJsonConstraintTransfer
    ): bool {
        $lockedVersion = $composerLockConstraintTransfer->getVersion();
        $expectedLockedVersion = sprintf('~%s', $lockedVersion);

        return $expectedLockedVersion === $composerJsonConstraintTransfer->getVersion();
    }
}
