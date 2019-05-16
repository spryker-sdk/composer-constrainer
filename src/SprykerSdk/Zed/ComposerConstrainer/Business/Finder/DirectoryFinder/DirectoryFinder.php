<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Zed\ComposerConstrainer\Business\Finder\DirectoryFinder;

use Generated\Shared\Transfer\ModuleTransfer;
use Generated\Shared\Transfer\UsedModuleCollectionTransfer;
use Generated\Shared\Transfer\UsedModuleTransfer;
use SprykerSdk\Zed\ComposerConstrainer\Business\Finder\UsedModuleFinderInterface;
use SprykerSdk\Zed\ComposerConstrainer\ComposerConstrainerConfig;
use SprykerSdk\Zed\ComposerConstrainer\Dependency\Facade\ComposerConstrainerToModuleFinderFacadeInterface;
use Symfony\Component\Finder\Finder;
use Symfony\Component\Finder\SplFileInfo;

class DirectoryFinder implements UsedModuleFinderInterface
{
    /**
     * @var \SprykerSdk\Zed\ComposerConstrainer\ComposerConstrainerConfig
     */
    protected $config;

    /**
     * @var \SprykerSdk\Zed\ComposerConstrainer\Dependency\Facade\ComposerConstrainerToModuleFinderFacadeInterface
     */
    protected $moduleFinderFacade;

    /**
     * @var \Generated\Shared\Transfer\ModuleTransfer[]|null
     */
    protected $modules;

    /**
     * @param \SprykerSdk\Zed\ComposerConstrainer\ComposerConstrainerConfig $config
     */
    public function __construct(ComposerConstrainerConfig $config, ComposerConstrainerToModuleFinderFacadeInterface $moduleFinderFacade)
    {
        $this->config = $config;
        $this->moduleFinderFacade = $moduleFinderFacade;
    }

    /**
     * @return \Generated\Shared\Transfer\UsedModuleCollectionTransfer
     */
    public function find(): UsedModuleCollectionTransfer
    {
        $usedModuleCollectionTransfer = new UsedModuleCollectionTransfer();

        $pathsToModules = $this->getPathsToModules();

        if (!is_array($pathsToModules) || count($pathsToModules) === 0) {
            return $usedModuleCollectionTransfer;
        }

        foreach ($this->createFinder($pathsToModules) as $splFileInfo) {
            $usedModuleCollectionTransfer = $this->addUsedModules($usedModuleCollectionTransfer, $splFileInfo);
        }

        return $usedModuleCollectionTransfer;
    }

    /**
     * @return array
     */
    protected function getPathsToModules(): array
    {
        return glob($this->config->getSourceDirectory() . '*/*', GLOB_ONLYDIR | GLOB_NOSORT);
    }

    /**
     * @param array $pathsToModules
     *
     * @return \Symfony\Component\Finder\Finder|\Symfony\Component\Finder\SplFileInfo
     */
    protected function createFinder(array $pathsToModules): Finder
    {
        return (new Finder())->directories()->in($pathsToModules)->depth('<= 2');
    }

    /**
     * @param \Generated\Shared\Transfer\UsedModuleCollectionTransfer $usedModuleCollectionTransfer
     * @param \Symfony\Component\Finder\SplFileInfo $splFileInfo
     *
     * @return \Generated\Shared\Transfer\UsedModuleCollectionTransfer
     */
    protected function addUsedModules(UsedModuleCollectionTransfer $usedModuleCollectionTransfer, SplFileInfo $splFileInfo): UsedModuleCollectionTransfer
    {
        $pathFragments = explode(DIRECTORY_SEPARATOR, $splFileInfo->getPathname());
        $positionOfSrcDirectory = array_search('src', $pathFragments);

        $moduleName = $pathFragments[$positionOfSrcDirectory + 3];
        $moduleTransfer = $this->findModuleTransfer($moduleName);

        if ($moduleTransfer === null) {
            return $usedModuleCollectionTransfer;
        }

        $organizationName = $moduleTransfer->getOrganization()->getName();

        $usedModuleTransfer = new UsedModuleTransfer();
        $usedModuleTransfer
            ->setOrganization($organizationName)
            ->setModule($moduleName);

        $usedModuleCollectionTransfer->addUsedModule($usedModuleTransfer);

        return $usedModuleCollectionTransfer;
    }

    /**
     * @param string $moduleName
     *
     * @return \Generated\Shared\Transfer\ModuleTransfer|null
     */
    protected function findModuleTransfer(string $moduleName): ?ModuleTransfer
    {
        $foundModules = [];
        foreach ($this->getModules() as $moduleTransfer) {
            if ($moduleTransfer->getName() === $moduleName) {
                $foundModules[] = $moduleTransfer;
            }
        }

        if (count($foundModules) === 1) {
            return current($foundModules);
        }

        return null;
    }

    /**
     * @return \Generated\Shared\Transfer\ModuleTransfer[]
     */
    protected function getModules(): array
    {
        if ($this->modules === null) {
            $this->modules = $this->moduleFinderFacade->getModules();
        }

        return $this->modules;
    }
}
