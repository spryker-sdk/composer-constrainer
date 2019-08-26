<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Zed\ComposerConstrainer\Business\Validator;

use Generated\Shared\Transfer\ComposerConstraintCollectionTransfer;
use Generated\Shared\Transfer\ComposerConstraintTransfer;
use Generated\Shared\Transfer\ConstraintMessageTransfer;
use Generated\Shared\Transfer\ConstraintTransfer;
use Generated\Shared\Transfer\ConstraintValidationResultTransfer;
use Generated\Shared\Transfer\UsedModuleTransfer;
use SprykerSdk\Zed\ComposerConstrainer\Business\Composer\ComposerJsonReaderInterface;
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
     * @var \SprykerSdk\Zed\ComposerConstrainer\Business\Composer\ComposerJsonReaderInterface
     */
    protected $composerJsonReader;

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
     * @param \SprykerSdk\Zed\ComposerConstrainer\Business\Composer\ComposerJsonReaderInterface $composerJsonReader
     * @param \SprykerSdk\Zed\ComposerConstrainer\Business\Version\ExpectedVersionBuilderInterface $expectedVersionBuilder
     */
    public function __construct(
        FinderInterface $usedModuleFinder,
        ComposerJsonReaderInterface $composerJsonReader,
        ExpectedVersionBuilderInterface $expectedVersionBuilder
    ) {
        $this->usedModuleFinder = $usedModuleFinder;
        $this->composerJsonReader = $composerJsonReader;
        $this->expectedVersionBuilder = $expectedVersionBuilder;
    }

    /**
     * @return \Generated\Shared\Transfer\ConstraintValidationResultTransfer
     */
    public function validateConstraints(): ConstraintValidationResultTransfer
    {
        $usedModules = $this->getUsedModulesAsComposerName();
        $composerConstraints = $this->getComposerConstraints();

        $constraintValidationResultTransfer = new ConstraintValidationResultTransfer();

        if (count($usedModules) === 0) {
            return $constraintValidationResultTransfer;
        }

        foreach ($usedModules as $composerName) {
            if (!isset($composerConstraints[$composerName])) {
                $constraintTransfer = new ConstraintTransfer();
                $constraintTransfer
                    ->setName($composerName)
                    ->setVersion('n/a');

                $messageTransfer = new ConstraintMessageTransfer();
                $messageTransfer->setMessage(sprintf(
                    'Expected to find a constraint for "%s" in your composer.json, but none found.',
                    $composerName
                ));

                $constraintTransfer->addMessage($messageTransfer);
                $constraintValidationResultTransfer->addInvalidConstraint($constraintTransfer);
            }

            if (isset($composerConstraints[$composerName]) && !$this->isVersionValid($composerConstraints[$composerName])) {
                $constraintTransfer = new ConstraintTransfer();
                $constraintTransfer
                    ->setName($composerName)
                    ->setVersion($composerConstraints[$composerName]);

                $messageTransfer = new ConstraintMessageTransfer();
                $messageTransfer->setMessage(sprintf(
                    'Expected version "%s" but current version is "%s"',
                    $this->expectedVersionBuilder->buildExpectedVersion($composerConstraints[$composerName]),
                    $composerConstraints[$composerName]
                ));

                $constraintTransfer->addMessage($messageTransfer);
                $constraintValidationResultTransfer->addInvalidConstraint($constraintTransfer);
            }
        }

        return $constraintValidationResultTransfer;
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
     * @return string[]
     */
    protected function getComposerConstraints(): array
    {
        $composerConstraintCollectionTransfer = $this->getComposerConstraintCollectionTransfer();
        $composerConstraints = [];

        foreach ($composerConstraintCollectionTransfer->getComposerConstraints() as $composerConstraintTransfer) {
            $composerConstraints[$composerConstraintTransfer->getName()] = $composerConstraintTransfer->getVersion();
        }

        return $composerConstraints;
    }

    /**
     * @return \Generated\Shared\Transfer\ComposerConstraintCollectionTransfer
     */
    protected function getComposerConstraintCollectionTransfer(): ComposerConstraintCollectionTransfer
    {
        $composerJsonArray = $this->composerJsonReader->read();

        $composerConstraintCollectionTransfer = new ComposerConstraintCollectionTransfer();

        $this->addToConstrainCollectionTransfer($composerConstraintCollectionTransfer, $composerJsonArray, 'require');
        $this->addToConstrainCollectionTransfer($composerConstraintCollectionTransfer, $composerJsonArray, 'require-dev');

        return $composerConstraintCollectionTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\ComposerConstraintCollectionTransfer $composerConstraintCollectionTransfer
     * @param array $composerJsonAsArray
     * @param string $key
     *
     * @return \Generated\Shared\Transfer\ComposerConstraintCollectionTransfer
     */
    protected function addToConstrainCollectionTransfer(
        ComposerConstraintCollectionTransfer $composerConstraintCollectionTransfer,
        array $composerJsonAsArray,
        string $key
    ): ComposerConstraintCollectionTransfer {
        if (!isset($composerJsonAsArray[$key])) {
            return $composerConstraintCollectionTransfer;
        }

        foreach ($composerJsonAsArray[$key] as $name => $version) {
            $composerConstraintTransfer = new ComposerConstraintTransfer();
            $composerConstraintTransfer
                ->setName($name)
                ->setVersion($version)
                ->setIsDev($key === 'require-dev');

            $composerConstraintCollectionTransfer->addComposerConstraint($composerConstraintTransfer);
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
     * @param string $currentVersion
     *
     * @return bool
     */
    protected function isVersionValid(string $currentVersion): bool
    {
        $currentVersions = explode('|', $currentVersion);
        foreach ($currentVersions as $currentVersion) {
            $currentVersion = trim($currentVersion);

            if ($currentVersion[0] === '^' && $currentVersion[1] !== '0') {
                return false;
            }
        }

        return true;
    }
}
