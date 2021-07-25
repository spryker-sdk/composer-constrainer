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
        $composerConstraintCollectionTransfer = new ComposerConstraintCollectionTransfer();

        $usedModuleCollectionTransfer = $this->usedModuleFinder->find();
        if ($usedModuleCollectionTransfer->getUsedModules()->count() === 0) {
            return $composerConstraintCollectionTransfer;
        }

        $composerJsonConstraints = $this->getComposerJsonConstraints();
        $composerLockConstraints = $this->getComposerLockConstraints();

        $composerConstraintTransfers = [];
        foreach ($usedModuleCollectionTransfer->getUsedModules() as $usedModuleTransfer) {
            $packageName = $usedModuleTransfer->getPackageName();

            $moduleInfo = (new ComposerConstraintModuleInfoTransfer())
                ->setIsCustomised($usedModuleTransfer->getIsCustomised())
                ->setIsConfigured($usedModuleTransfer->getIsConfigured())
                ->setCustomisedLogicLineCount($usedModuleTransfer->getCustomisedLineCount())
                ->setJsonConstraintLock("") // default value
                ->setJsonVersion("") // default value
                ->setLockedVersion(""); // default value

            if (isset($composerJsonConstraints[$packageName])) {
                $moduleInfo
                    ->setJsonConstraintLock($this->translateVersionToVersionLock($composerJsonConstraints[$packageName]->getVersion()))
                    ->setJsonVersion(str_replace(['^', '~'], '', $composerJsonConstraints[$packageName]->getVersion()));
            }

            if (isset($composerLockConstraints[$packageName])) {
                $moduleInfo->setLockedVersion($composerLockConstraints[$packageName]->getVersion());
            }

            $moduleInfo->setExpectedConstraintLock($this->getExpectedConstraintLock($moduleInfo));

            $composerConstraintTransfer = (new ComposerConstraintTransfer())
                ->setName($usedModuleTransfer->getPackageName())
                ->setModuleInfo($moduleInfo);

            $composerConstraintTransfers[$packageName] = $composerConstraintTransfer;
        }

        $composerConstraintTransfers = $this->removeCorrectModules($composerConstraintTransfers);
        ksort($composerConstraintTransfers);

        return $composerConstraintCollectionTransfer->setComposerConstraints(new \ArrayObject($composerConstraintTransfers));
    }

    protected function getExpectedConstraintLock(ComposerConstraintModuleInfoTransfer $moduleInfo): string
    {
        if ($moduleInfo->getIsCustomised()) {
            return "~";
        }

        return "^"; // all dependency needs to be locked at least on major
    }

    /**
     * @param ComposerConstraintTransfer[] $composerConstraintTransfers
     *
     * @return ComposerConstraintTransfer[]
     */
    protected function removeCorrectModules(array $composerConstraintTransfers): array
    {
        return array_filter($composerConstraintTransfers, function(ComposerConstraintTransfer $composerConstraintTransfer): bool {
            if ($composerConstraintTransfer->getModuleInfo()->getExpectedConstraintLock() === $composerConstraintTransfer->getModuleInfo()->getJsonConstraintLock() && $composerConstraintTransfer->getModuleInfo()->getCustomisedLogicLineCount() === 0) {
                return false;
            }

            return true;
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
    protected function translateVersionToVersionLock(string $version): string
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
