<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Zed\ComposerConstrainer\Business\Finder\UsedForeignModuleFinder;

use Closure;
use Generated\Shared\Transfer\UsedModuleCollectionTransfer;
use Generated\Shared\Transfer\UsedModuleTransfer;
use ReflectionClass;
use Roave\BetterReflection\BetterReflection;
use Roave\BetterReflection\Reflector\ClassReflector;
use Roave\BetterReflection\SourceLocator\Type\SingleFileSourceLocator;
use SprykerSdk\Zed\ComposerConstrainer\Business\Finder\FinderInterface;
use SprykerSdk\Zed\ComposerConstrainer\ComposerConstrainerConfig;
use Symfony\Component\Finder\Finder;
use Symfony\Component\Finder\SplFileInfo;

class UsedForeignModuleFinder implements FinderInterface
{
    /**
     * @var \SprykerSdk\Zed\ComposerConstrainer\ComposerConstrainerConfig
     */
    protected $config;

    /**
     * @param \SprykerSdk\Zed\ComposerConstrainer\ComposerConstrainerConfig $config
     */
    public function __construct(ComposerConstrainerConfig $config)
    {
        $this->config = $config;
    }

    /**
     * @return \Generated\Shared\Transfer\UsedModuleCollectionTransfer
     */
    public function find(): UsedModuleCollectionTransfer
    {
        $usedModuleCollectionTransfer = new UsedModuleCollectionTransfer();

        if (!is_dir($this->config->getSourceDirectory())) {
            return $usedModuleCollectionTransfer;
        }

        foreach ($this->createFinder() as $splFileInfo) {
            $usedModuleCollectionTransfer = $this->addUsedForeignModules($usedModuleCollectionTransfer, $splFileInfo);
        }

        return $usedModuleCollectionTransfer;
    }


    /**
     * @return \Symfony\Component\Finder\Finder|\Symfony\Component\Finder\SplFileInfo[]
     */
    protected function createFinder(): Finder
    {
        $finder = new Finder();
        $finder
            ->files()
            ->in($this->config->getSourceDirectory())
            ->filter($this->getFileFilter())
            ->exclude(['Generated', 'Orm'])
            ->name('*.php');

        return $finder;
    }

    /**
     * @return \Closure
     */
    protected function getFileFilter(): Closure
    {
        return function (SplFileInfo $fileInfo) {
            $filePath = $fileInfo->getPathname();
            if (preg_match('/src\/(.*?)\/(.*?)\/(.*?)Config.php|src\/(.*?)\/(.*?)\/(.*?)DependencyProvider.php/', $filePath)) {
                return false;
            }

            return true;
        };
    }

    /**
     * @param \Generated\Shared\Transfer\UsedModuleCollectionTransfer $usedModuleCollectionTransfer
     * @param \Symfony\Component\Finder\SplFileInfo $splFileInfo
     *
     * @return \Generated\Shared\Transfer\UsedModuleCollectionTransfer
     */
    protected function addUsedForeignModules(UsedModuleCollectionTransfer $usedModuleCollectionTransfer, SplFileInfo $splFileInfo): UsedModuleCollectionTransfer
    {
        $fileContent = $splFileInfo->getContents();
        $coreNamespaces = $this->config->getCoreNamespaces();
        $pattern = sprintf('/(?<organization>%s)\\\\(Client|Glue|Shared|Service|Yves|Zed)\\\\(?<module>\w*)\\\\/', implode('|', $coreNamespaces));
        foreach ($this->getUsedClassesInFile($fileContent) as $usedClassName) {
            if (!preg_match_all($pattern, $usedClassName, $matches, PREG_SET_ORDER)) {

                $usedModuleTransfer = $this->getUsedModuleByClassName($usedClassName);
                if ($usedModuleTransfer) {
                    $usedModuleCollectionTransfer->addUsedModule($usedModuleTransfer);
                }
            }
        }

        return $usedModuleCollectionTransfer;
    }

    /**
     * @param string $className
     *
     * @return \Spryker\Shared\Kernel\Transfer\AbstractTransfer\UsedModuleTransfer|null
     */
    protected function getUsedModuleByClassName(string $className): ?UsedModuleTransfer
    {
        $usedModuleTransfer = null;
        if ($this->isVendorClass($className)) {
            $usedModuleTransfer = new UsedModuleTransfer();
            $usedModuleTransfer
                ->setOrganization('Organization')
                ->setModule('Module');
        }

        return $usedModuleTransfer;
    }

    protected function isVendorClass(string $className): bool
    {
        $reflection = new ReflectionClass($className);
        $fileName = $reflection->getFileName();
        $pattern = sprintf('/%s/',str_replace('/', '\/', $this->config->getVendorDirectory()));
        return preg_match($pattern, $fileName);
    }


    /**
     * @param string $fileContent
     *
     * @return string[]
     */
    protected function getUsedClassesInFile(string $fileContent): array
    {
        $pattern = '/use (.*);/';
        $usedClasses = [];
        if (preg_match_all($pattern, $fileContent, $matches, PREG_SET_ORDER)) {
            foreach ($matches as $match) {
                $usedClasses[] = explode(' ', $match[1])[0];
            }
        }

        return $usedClasses;
    }
}
