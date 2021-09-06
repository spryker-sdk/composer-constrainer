<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Zed\ComposerConstrainer\Business\Validator;

use ArrayObject;
use Generated\Shared\Transfer\ComposerConstraintCollectionTransfer;
use Generated\Shared\Transfer\ComposerConstraintModuleInfoTransfer;
use Generated\Shared\Transfer\ComposerConstraintTransfer;
use SprykerSdk\Zed\ComposerConstrainer\Business\Composer\ComposerJson\ComposerJsonReaderInterface;
use SprykerSdk\Zed\ComposerConstrainer\Business\Composer\ComposerLock\ComposerLockReaderInterface;
use SprykerSdk\Zed\ComposerConstrainer\Business\Finder\FinderInterface;
use SprykerSdk\Zed\ComposerConstrainer\ComposerConstrainerConfig;

class StrictConstraintValidator implements ConstraintValidatorInterface
{
    /**
     * @var \SprykerSdk\Zed\ComposerConstrainer\ComposerConstrainerConfig
     */
    protected $config;

    /**
     * @var \SprykerSdk\Zed\ComposerConstrainer\Business\Finder\FinderInterface
     */
    protected $strictFinder;

    /**
     * @var \SprykerSdk\Zed\ComposerConstrainer\Business\Composer\ComposerJson\ComposerJsonReaderInterface
     */
    protected $composerJsonReader;

    /**
     * @var \SprykerSdk\Zed\ComposerConstrainer\Business\Composer\ComposerLock\ComposerLockReaderInterface
     */
    protected $composerLockReader;

    /**
     * @param \SprykerSdk\Zed\ComposerConstrainer\ComposerConstrainerConfig $config
     * @param \SprykerSdk\Zed\ComposerConstrainer\Business\Finder\FinderInterface $strictFinder
     * @param \SprykerSdk\Zed\ComposerConstrainer\Business\Composer\ComposerJson\ComposerJsonReaderInterface $composerJsonReader
     * @param \SprykerSdk\Zed\ComposerConstrainer\Business\Composer\ComposerLock\ComposerLockReaderInterface $composerLockReader
     */
    public function __construct(
        ComposerConstrainerConfig $config,
        FinderInterface $strictFinder,
        ComposerJsonReaderInterface $composerJsonReader,
        ComposerLockReaderInterface $composerLockReader
    ) {
        $this->config = $config;
        $this->strictFinder = $strictFinder;
        $this->composerJsonReader = $composerJsonReader;
        $this->composerLockReader = $composerLockReader;
    }

    /**
     * @return \Generated\Shared\Transfer\ComposerConstraintCollectionTransfer
     */
    public function validateConstraints(): ComposerConstraintCollectionTransfer
    {
        $composerConstraintCollectionTransfer = new ComposerConstraintCollectionTransfer();

        $usedModuleCollectionTransfer = $this->strictFinder->find();
        if ($usedModuleCollectionTransfer->getUsedModules()->count() === 0) {
            return $composerConstraintCollectionTransfer;
        }

        $composerDefinedConstraints = $this->composerJsonReader->getConstraints();
        $composerLockConstraints = $this->composerLockReader->getConstraints();
        $composerLockFeatureInheritedConstraints = $this->getComposerLockFeatureInheritedConstraints($composerDefinedConstraints, $composerLockConstraints);

        $composerConstraintTransfers = [];
        foreach ($usedModuleCollectionTransfer->getUsedModules() as $usedModuleTransfer) {
            $usedModulePackageName = $usedModuleTransfer->getPackageName();

            $usedModuleInfo = $this->createModuleInfoTransfer(
                $usedModuleTransfer->getIsCustomized(),
                $usedModuleTransfer->getIsConfigured(),
                (int)$usedModuleTransfer->getCustomizedLineCount(),
                $usedModuleTransfer->getConstraintReasons()
            );

            $usedModuleInfo = $this->setDefinedModuleInfo($usedModuleInfo, $composerDefinedConstraints[$usedModulePackageName] ?? null, $composerLockFeatureInheritedConstraints[$usedModulePackageName] ?? null);
            $usedModuleInfo = $this->setLockModuleInfo($usedModuleInfo, $composerLockConstraints[$usedModulePackageName] ?? null);

            $usedModuleInfo = $this->setExpectation($usedModuleInfo);

            $composerConstraintTransfers[$usedModulePackageName] = (new ComposerConstraintTransfer())
                ->setName($usedModulePackageName)
                ->setModuleInfo($usedModuleInfo);
        }

        $composerConstraintTransfers = $this->removeCorrectPackages($composerConstraintTransfers);

        ksort($composerConstraintTransfers);

        return $composerConstraintCollectionTransfer->setComposerConstraints(new ArrayObject($composerConstraintTransfers));
    }

    /**
     * @param bool|null $isCustomized
     * @param bool|null $isConfigured
     * @param int $customizedLineCount
     * @param string[] $reasons
     *
     * @return \Generated\Shared\Transfer\ComposerConstraintModuleInfoTransfer
     */
    protected function createModuleInfoTransfer(
        ?bool $isCustomized,
        ?bool $isConfigured,
        int $customizedLineCount,
        array $reasons
    ): ComposerConstraintModuleInfoTransfer {
        return (new ComposerConstraintModuleInfoTransfer())
            ->setIsCustomized($isCustomized)
            ->setIsConfigured($isConfigured)
            ->setCustomizedLineCount($customizedLineCount)
            ->setDefinedConstraintLock('')
            ->setDefinedVersion('')
            ->setLockedVersion('')
            ->setConstraintReasons($reasons);
    }

    /**
     * Specification:
     * - Calculates what lock and version are defined in composer.json for a given module.
     * - Uses feature inherited lock and version from composer.lock in case module is NOT defined in composer.json.
     *
     * @param \Generated\Shared\Transfer\ComposerConstraintModuleInfoTransfer $usedModuleInfo
     * @param \Generated\Shared\Transfer\ComposerConstraintTransfer|null $definedConstraint
     *
     * @return \Generated\Shared\Transfer\ComposerConstraintModuleInfoTransfer
     */
    protected function setDefinedModuleInfo(
        ComposerConstraintModuleInfoTransfer $usedModuleInfo,
        ?ComposerConstraintTransfer $definedConstraint = null,
        ?ComposerConstraintTransfer $featureConstraint = null
    ): ComposerConstraintModuleInfoTransfer {
        if ($definedConstraint === null && $featureConstraint === null) {
            return $usedModuleInfo;
        }

        $constraint = $definedConstraint;
        if ($constraint === null) {
            /** @var \Generated\Shared\Transfer\ComposerConstraintTransfer $constraint */
            $constraint = $featureConstraint;
        }

        $version = (string)$constraint->getVersion();
        $isDev = (bool)$constraint->getIsDev();

        return $usedModuleInfo
            ->setDefinedConstraintLock($this->extractConstraintLock($version))
            ->setDefinedVersion(str_replace(['^', '~'], '', $version))
            ->setIsDev($isDev);
    }

    /**
     * Specification:
     * - Calculates what version is defined in composer.lock for a given module.
     *
     * @param \Generated\Shared\Transfer\ComposerConstraintModuleInfoTransfer $usedModuleInfo
     * @param \Generated\Shared\Transfer\ComposerConstraintTransfer|null $lockConstraint
     *
     * @return \Generated\Shared\Transfer\ComposerConstraintModuleInfoTransfer
     */
    protected function setLockModuleInfo(
        ComposerConstraintModuleInfoTransfer $usedModuleInfo,
        ?ComposerConstraintTransfer $lockConstraint = null
    ): ComposerConstraintModuleInfoTransfer {
        if ($lockConstraint === null) {
            return $usedModuleInfo;
        }

        return $usedModuleInfo->setLockedVersion($lockConstraint->getVersion());
    }

    /**
     * Specification
     * - Used modules have either "public API" (^) or customized (~) lock
     * - Defined constraint lock is either missing or major (^), minor (~) or patch ("")
     * - If defined constraint is missing then module usage constraint drives the expectation
     * - If defined constraint is present then the smaller lock is the expectation
     *
     * @param \Generated\Shared\Transfer\ComposerConstraintModuleInfoTransfer $usedModuleInfo
     *
     * @return \Generated\Shared\Transfer\ComposerConstraintModuleInfoTransfer
     */
    protected function setExpectation(ComposerConstraintModuleInfoTransfer $usedModuleInfo): ComposerConstraintModuleInfoTransfer
    {
        $moduleUsageConstraintLock = $usedModuleInfo->getIsCustomized() ? '~' : '^';
        $definedConstraintLock = $usedModuleInfo->getDefinedConstraintLock();
        $hasDefinedVersion = !empty($usedModuleInfo->getDefinedVersion());

        $expectedConstraintLock = $moduleUsageConstraintLock;
        if ($hasDefinedVersion) {
            $expectedConstraintLock = $definedConstraintLock;
            if ($definedConstraintLock === '^' && $moduleUsageConstraintLock === '~') {
                $expectedConstraintLock = '~';
            }
        }

        return $usedModuleInfo
            ->setExpectedConstraintLock($expectedConstraintLock)
            ->setExpectedVersion($usedModuleInfo->getDefinedVersion() ?: $usedModuleInfo->getLockedVersion());
    }

    /**
     * Specification
     * - Ignored packages are removed.
     * - Line count MUST be zero to be considered properly developed except if it is disabled in configuration.
     * - Expected and defined constraint lock need to match to be considered will configured.
     *
     * @param \Generated\Shared\Transfer\ComposerConstraintTransfer[] $composerConstraintTransfers
     *
     * @return \Generated\Shared\Transfer\ComposerConstraintTransfer[]
     */
    protected function removeCorrectPackages(array $composerConstraintTransfers): array
    {
        $ignoredPackages = '#(' . implode('|', $this->config->getStrictValidationIgnoredPackages()) . ')#';
        $isIgnoreLineCount = $this->config->getIsIgnoreLineCount();

        return array_filter($composerConstraintTransfers, function (ComposerConstraintTransfer $composerConstraintTransfer) use ($ignoredPackages, $isIgnoreLineCount): bool {
            $isIgnoredPackage = (bool)preg_match($ignoredPackages, (string)$composerConstraintTransfer->getName());
            $isExpectedLockMatchesDefinedLock = $composerConstraintTransfer->getModuleInfoOrFail()->getExpectedConstraintLock() === $composerConstraintTransfer->getModuleInfoOrFail()->getDefinedConstraintLock();
            $noCustomizedLineCount = $isIgnoreLineCount || $composerConstraintTransfer->getModuleInfoOrFail()->getCustomizedLineCount() === 0;

            return $isIgnoredPackage || $isExpectedLockMatchesDefinedLock && $noCustomizedLineCount ? false : true;
        });
    }

    /**
     * @example "~3.7.1" => "~"
     * @example "^3.7.1" => "^"
     * @example "" => ""
     * @example "8.2.1" => ""
     *
     * @param string $version
     *
     * @return string
     */
    protected function extractConstraintLock(string $version): string
    {
        preg_match_all('#^(?<constraint>[\~\^])#', (string)$version, $match);

        return count($match['constraint']) < 1 ? '' : $match['constraint'][0];
    }

    /**
     * Specification
     * - Features MUST be locked by ~ or ^ or by exact version therefore anything else is ignored.
     * - Retrieves the Spryker constraint locks by features based on the existing defined constraint list.
     *
     * @param \Generated\Shared\Transfer\ComposerConstraintTransfer[] $composerDefinedConstraints
     * @param \Generated\Shared\Transfer\ComposerConstraintTransfer[] $composerLockConstraints
     *
     * @return \Generated\Shared\Transfer\ComposerConstraintTransfer[]
     */
    protected function getComposerLockFeatureInheritedConstraints(array $composerDefinedConstraints, array $composerLockConstraints): array
    {
        /** @var string[] $composerDefinedFeatureNames */
        $composerDefinedFeatureNames = [];
        foreach ($composerDefinedConstraints as $composerDefinedConstraint) {
            if (!preg_match('#^spryker-feature/#', (string)$composerDefinedConstraint->getName())) {
                continue;
            }

            if (!preg_match('#^[~^]?[0-9]#', (string)$composerDefinedConstraint->getVersion())) {
                continue;
            }

            $composerDefinedFeatureNames[] = $composerDefinedConstraint->getName();
        }

        $mergedComposerLockFeatureInheritedConstraints = [];
        foreach ($composerDefinedFeatureNames as $composerDefinedFeatureName) {
            if (!isset($composerLockConstraints[$composerDefinedFeatureName])) {
                continue;
            }

            foreach ($composerLockConstraints[$composerDefinedFeatureName]->getDefinedConstraints() as $composerLockedFeatureInheritedConstraintTransfer) {
                if (!preg_match('#^spryker#', (string)$composerLockedFeatureInheritedConstraintTransfer->getName())) {
                    continue;
                }

                $mergedComposerLockFeatureInheritedConstraints[$composerLockedFeatureInheritedConstraintTransfer->getName()] = $composerLockedFeatureInheritedConstraintTransfer;
            }
        }

        return $mergedComposerLockFeatureInheritedConstraints;
    }
}
