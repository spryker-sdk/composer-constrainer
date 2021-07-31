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
     * @param \SprykerSdk\Zed\ComposerConstrainer\Business\Finder\FinderInterface $verboseFinder
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

        $composerConstraintTransfers = [];
        foreach ($usedModuleCollectionTransfer->getUsedModules() as $usedModuleTransfer) {
            $usedModulePackageName = $usedModuleTransfer->getPackageName();

            $usedModuleInfo = $this->createModuleInfoTransfer(
                $usedModuleTransfer->getIsCustomized(),
                $usedModuleTransfer->getIsConfigured(),
                (int)$usedModuleTransfer->getCustomizedLineCount(),
                $usedModuleTransfer->getConstraintReasons()
            );

            $usedModuleInfo = $this->setDefinedModuleInfo($usedModuleInfo, $composerDefinedConstraints[$usedModulePackageName] ?? null);
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
     * @param bool $isCustomized
     * @param bool $isConfigured
     * @param int $customizedLineCount
     * @param string[] $reasons
     *
     * @return \Generated\Shared\Transfer\ComposerConstraintModuleInfoTransfer
     */
    protected function createModuleInfoTransfer(bool $isCustomized, bool $isConfigured, int $customizedLineCount, array $reasons): ComposerConstraintModuleInfoTransfer
    {
        return (new ComposerConstraintModuleInfoTransfer())
            ->setIsCustomized($isCustomized)
            ->setIsConfigured($isConfigured)
            ->setCustomizedLineCount($customizedLineCount)
            ->setDefinedConstraintLock("")
            ->setDefinedVersion("")
            ->setLockedVersion("")
            ->setConstraintReasons($reasons);
    }

    /**
     * @param \Generated\Shared\Transfer\ComposerConstraintModuleInfoTransfer $usedModuleInfo
     * @param \Generated\Shared\Transfer\ComposerConstraintTransfer|null $definedConstraint
     *
     * @return \Generated\Shared\Transfer\ComposerConstraintModuleInfoTransfer
     */
    protected function setDefinedModuleInfo(ComposerConstraintModuleInfoTransfer $usedModuleInfo, ?ComposerConstraintTransfer $definedConstraint = null): ComposerConstraintModuleInfoTransfer
    {
        if ($definedConstraint === null) {
            return $usedModuleInfo;
        }

        return $usedModuleInfo
            ->setDefinedConstraintLock($this->extractConstraintLock($definedConstraint->getVersion()))
            ->setDefinedVersion(str_replace(['^', '~'], '', $definedConstraint->getVersion()));
    }

    /**
     * @param \Generated\Shared\Transfer\ComposerConstraintModuleInfoTransfer $usedModuleInfo
     * @param \Generated\Shared\Transfer\ComposerConstraintTransfer|null $lockConstraint
     *
     * @return \Generated\Shared\Transfer\ComposerConstraintModuleInfoTransfer
     */
    protected function setLockModuleInfo(ComposerConstraintModuleInfoTransfer $usedModuleInfo, ?ComposerConstraintTransfer $lockConstraint = null): ComposerConstraintModuleInfoTransfer
    {
        if ($lockConstraint === null) {
            return $usedModuleInfo;
        }

        return $usedModuleInfo->setLockedVersion($lockConstraint->getVersion());
    }

    /**
     * Specification
     * - Any core module that is customised, needs to be locked as ~
     * - Any core module that is used, needs to be locked as ^
     *
     * @param \Generated\Shared\Transfer\ComposerConstraintModuleInfoTransfer $moduleInfo
     *
     * @return \Generated\Shared\Transfer\ComposerConstraintModuleInfoTransfer
     */
    protected function setExpectation(ComposerConstraintModuleInfoTransfer $usedModuleInfo): ComposerConstraintModuleInfoTransfer
    {
        return $usedModuleInfo->setExpectedConstraintLock($usedModuleInfo->getIsCustomized() ? "~" : "^");
    }

    /**
     * Specification
     * - Ignored packages are removed.
     * - Line count MUST be zero to be considered well configured.
     * - Expected and defined constraint lock need to match to be considered will configured.
     *
     * @param \Generated\Shared\Transfer\ComposerConstraintTransfer[] $composerConstraintTransfers
     *
     * @return \Generated\Shared\Transfer\ComposerConstraintTransfer[]
     */
    protected function removeCorrectPackages(array $composerConstraintTransfers): array
    {
        $ignoredPackages = '#(' . implode('|', $this->config->getStrictValidationIgnoredPackages()) . ')#';

        return array_filter($composerConstraintTransfers, function (ComposerConstraintTransfer $composerConstraintTransfer) use ($ignoredPackages): bool {
            $isIgnoredPackage = (bool)preg_match($ignoredPackages, $composerConstraintTransfer->getName());
            $isExpectedMatchesDefined = $composerConstraintTransfer->getModuleInfo()->getExpectedConstraintLock() === $composerConstraintTransfer->getModuleInfo()->getDefinedConstraintLock();
            $noCustomizedLineCount = $composerConstraintTransfer->getModuleInfo()->getCustomizedLineCount() === 0;

            return $isIgnoredPackage || $isExpectedMatchesDefined && $noCustomizedLineCount ? false : true;
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

        return count($match['constraint']) < 1 ? "" : $match['constraint'][0];
    }
}
