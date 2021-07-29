<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Zed\ComposerConstrainer\Business\Validator;

use Generated\Shared\Transfer\ComposerConstraintCollectionTransfer;
use Generated\Shared\Transfer\ComposerConstraintModuleInfoTransfer;
use Generated\Shared\Transfer\ComposerConstraintTransfer;
use Generated\Shared\Transfer\ConstraintMessageTransfer;
use Generated\Shared\Transfer\UsedModuleTransfer;
use SprykerSdk\Zed\ComposerConstrainer\Business\Composer\ComposerJson\ComposerJsonReaderInterface;
use SprykerSdk\Zed\ComposerConstrainer\Business\Composer\ComposerLock\ComposerLockReaderInterface;
use SprykerSdk\Zed\ComposerConstrainer\Business\Finder\FinderInterface;
use Symfony\Component\Finder\Finder;
use Symfony\Component\Finder\SplFileInfo;

class VerboseConstraintValidator implements ConstraintValidatorInterface
{
    /**
     * @var \SprykerSdk\Zed\ComposerConstrainer\Business\Finder\FinderInterface
     */
    protected $verboseFinder;

    /**
     * @var \SprykerSdk\Zed\ComposerConstrainer\Business\Composer\ComposerJson\ComposerJsonReaderInterface
     */
    protected $composerJsonReader;

    /**
     * @var \SprykerSdk\Zed\ComposerConstrainer\Business\Composer\ComposerLock\ComposerLockReaderInterface
     */
    protected $composerLockReader;

    protected $projectOnlyPackageNamePatterns = [

        "spryker/kernel$", # kernel has completley different customisation rules

    ];

    /**
     * @param \SprykerSdk\Zed\ComposerConstrainer\Business\Finder\FinderInterface $verboseFinder
     * @param \SprykerSdk\Zed\ComposerConstrainer\Business\Composer\ComposerJson\ComposerJsonReaderInterface $composerJsonReader
     * @param \SprykerSdk\Zed\ComposerConstrainer\Business\Composer\ComposerLock\ComposerLockReaderInterface $composerLockReader
     */
    public function __construct(
        FinderInterface $verboseFinder,
        ComposerJsonReaderInterface $composerJsonReader,
        ComposerLockReaderInterface $composerLockReader
    ) {
        $this->verboseFinder = $verboseFinder;
        $this->composerJsonReader = $composerJsonReader;
        $this->composerLockReader = $composerLockReader;
    }

    /**
     * @return \Generated\Shared\Transfer\ComposerConstraintCollectionTransfer
     */
    public function validateConstraints(): ComposerConstraintCollectionTransfer
    {
        $composerConstraintCollectionTransfer = new ComposerConstraintCollectionTransfer();

        $usedModuleCollectionTransfer = $this->verboseFinder->find();
        if ($usedModuleCollectionTransfer->getUsedModules()->count() === 0) {
            return $composerConstraintCollectionTransfer;
        }

        $composerJsonConstraints = $this->getComposerJsonConstraints();
        $composerLockConstraints = $this->getComposerLockConstraints();

        $composerConstraintTransfers = [];
        /** @var \Generated\Shared\Transfer\UsedModuleTransfer $usedModuleTransfer */
        foreach ($usedModuleCollectionTransfer->getUsedModules() as $usedModuleTransfer) {
            $usedModulePackageName = $usedModuleTransfer->getPackageName();

            $usedModuleInfo = $this->createModuleInfoTransfer(
                $usedModuleTransfer->getIsCustomised(),
                $usedModuleTransfer->getIsConfigured(),
                (int)$usedModuleTransfer->getCustomisedLineCount(),
                $usedModuleTransfer->getConstraintReasons()
            );

            $usedModuleInfo = $this->setJsonModuleInfo($usedModuleInfo, $composerJsonConstraints[$usedModulePackageName] ?? null);
            $usedModuleInfo = $this->setLockModuleInfo($usedModuleInfo, $composerLockConstraints[$usedModulePackageName] ?? null);

            $usedModuleInfo = $this->setExpectation($usedModuleInfo);

            $composerConstraintTransfers[$usedModulePackageName] = (new ComposerConstraintTransfer())
                ->setName($usedModulePackageName)
                ->setModuleInfo($usedModuleInfo);
        }

        $composerConstraintTransfers = $this->removeCorrectModules($composerConstraintTransfers);

        ksort($composerConstraintTransfers);

        return $composerConstraintCollectionTransfer->setComposerConstraints(new \ArrayObject($composerConstraintTransfers));
    }

    protected function createModuleInfoTransfer(bool $isCustomised, bool $isConfigured, int $customizedLineCount, array $reasons) : ComposerConstraintModuleInfoTransfer
    {
        return (new ComposerConstraintModuleInfoTransfer())
            ->setIsCustomised($isCustomised)
            ->setIsConfigured($isConfigured)
            ->setCustomisedLogicLineCount($customizedLineCount)
            ->setJsonConstraintLock("")
            ->setJsonVersion("")
            ->setLockedVersion("")
            ->setConstraintReasons($reasons);
    }

    protected function setJsonModuleInfo(ComposerConstraintModuleInfoTransfer $usedModuleInfo, ComposerConstraintTransfer $jsonConstraint = null): ComposerConstraintModuleInfoTransfer
    {
        if ($jsonConstraint === null) {
            return $usedModuleInfo;
        }

        return $usedModuleInfo
            ->setJsonConstraintLock($this->extractConstraintLock($jsonConstraint->getVersion()))
            ->setJsonVersion(str_replace(['^', '~'], '', $jsonConstraint->getVersion()));
    }

    protected function setLockModuleInfo(ComposerConstraintModuleInfoTransfer $usedModuleInfo, ComposerConstraintTransfer $lockConstraint = null): ComposerConstraintModuleInfoTransfer
    {
        if ($lockConstraint === null) {
            return $usedModuleInfo;
        }

        return $usedModuleInfo->setLockedVersion($lockConstraint->getVersion());
    }

    /**
     * @param ComposerConstraintModuleInfoTransfer $moduleInfo
     *
     * @return ComposerConstraintModuleInfoTransfer
     */
    protected function setExpectation(ComposerConstraintModuleInfoTransfer $usedModuleInfo): ComposerConstraintModuleInfoTransfer
    {
        return $usedModuleInfo->setExpectedConstraintLock($usedModuleInfo->getIsCustomised() ? "~" : "^");
    }

    /**
     * @param ComposerConstraintTransfer[] $composerConstraintTransfers
     *
     * @return ComposerConstraintTransfer[]
     */
    protected function removeCorrectModules(array $composerConstraintTransfers): array
    {
        $projectOnlyPackageNamePattern = '#(' . implode('|', $this->projectOnlyPackageNamePatterns). ')#';
        return array_filter($composerConstraintTransfers, function(ComposerConstraintTransfer $composerConstraintTransfer) use ($projectOnlyPackageNamePattern): bool {
            $isExpectedMatchesActual = $composerConstraintTransfer->getModuleInfo()->getExpectedConstraintLock() === $composerConstraintTransfer->getModuleInfo()->getJsonConstraintLock();
            $noCustomisedLineCouunt = $composerConstraintTransfer->getModuleInfo()->getCustomisedLogicLineCount() === 0;
            $isProjectOnlyModule = (bool)preg_match($projectOnlyPackageNamePattern, $composerConstraintTransfer->getName());

            return $isExpectedMatchesActual && $noCustomisedLineCouunt || $isProjectOnlyModule ? false : true;
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
}
