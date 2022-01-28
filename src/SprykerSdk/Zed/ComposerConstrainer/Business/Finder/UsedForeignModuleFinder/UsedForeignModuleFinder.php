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
use RuntimeException;
use SprykerSdk\Zed\ComposerConstrainer\Business\Composer\ComposerJson\ComposerJsonReaderInterface;
use SprykerSdk\Zed\ComposerConstrainer\Business\Finder\FinderInterface;
use SprykerSdk\Zed\ComposerConstrainer\ComposerConstrainerConfig;
use Symfony\Component\Finder\Finder;
use Symfony\Component\Finder\SplFileInfo;

class UsedForeignModuleFinder implements FinderInterface
{
    /**
     * @var \SprykerSdk\Zed\ComposerConstrainer\Business\Composer\ComposerJson\ComposerJsonReaderInterface
     */
    protected $composerJsonReader;

    /**
     * @var \SprykerSdk\Zed\ComposerConstrainer\ComposerConstrainerConfig
     */
    protected $config;

    /**
     * @param \SprykerSdk\Zed\ComposerConstrainer\ComposerConstrainerConfig $config
     * @param \SprykerSdk\Zed\ComposerConstrainer\Business\Composer\ComposerJson\ComposerJsonReaderInterface $composerJsonReader
     */
    public function __construct(ComposerConstrainerConfig $config, ComposerJsonReaderInterface $composerJsonReader)
    {
        $this->config = $config;
        $this->composerJsonReader = $composerJsonReader;
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
            ->exclude($this->config->getExcludedNamespaces())
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
    protected function addUsedForeignModules(
        UsedModuleCollectionTransfer $usedModuleCollectionTransfer,
        SplFileInfo $splFileInfo
    ): UsedModuleCollectionTransfer {
        $fileContent = $splFileInfo->getContents();
        foreach ($this->getUsedClassNamesInFile($fileContent) as $usedClassName) {
            if (!$this->isExcludedClass($usedClassName) && $this->isVendorClass($usedClassName)) {
                $usedModuleTransfer = $this->getUsedModuleByClassName($usedClassName);
                if (!$usedModuleTransfer) {
                    continue;
                }
                $usedModuleCollectionTransfer->addUsedModule($usedModuleTransfer);
            }
        }

        return $usedModuleCollectionTransfer;
    }

    /**
     * @param string $className
     *
     * @return bool
     */
    protected function isExcludedClass(string $className): bool
    {
        $namespaces = array_merge(
            $this->config->getCoreNamespaces(),
            $this->config->getExcludedNamespaces(),
            $this->config->getProjectNamespaces(),
        );
        $pattern = sprintf('/(%s)\\\\/', implode('|', $namespaces));

        return (bool)preg_match_all($pattern, $className);
    }

    /**
     * @phpstan-param class-string $className
     *
     * @param string $className
     *
     * @return \Generated\Shared\Transfer\UsedModuleTransfer|null
     */
    protected function getUsedModuleByClassName(string $className): ?UsedModuleTransfer
    {
        $classFilename = $this->getClassFileNameByClassName($className);
        $composerJsonData = $this->getComposerJsonDataByClassFilename($classFilename);
        if (!$composerJsonData) {
            return null;
        }
        $packageName = explode('/', $composerJsonData['name']);
        $usedModuleTransfer = (new UsedModuleTransfer())
            ->setOrganization($packageName[0])
            ->setModule($packageName[1]);

        return $usedModuleTransfer;
    }

    /**
     * @param string $classFilename
     *
     * @return array
     */
    protected function getComposerJsonDataByClassFilename(string $classFilename): array
    {
        $pattern = sprintf('/(%s)([^\/]+\/+){2}/', str_replace('/', '\/', $this->config->getVendorDirectory()));
        preg_match($pattern, $classFilename, $matches);
        $composerJsonFilePath = $matches[0];
        $composerJsonData = $this->composerJsonReader->readFromFilePath($composerJsonFilePath);

        return $composerJsonData;
    }

    /**
     * @phpstan-param class-string $className
     *
     * @param string $className
     *
     * @return bool
     */
    protected function isVendorClass(string $className): bool
    {
        $filename = $this->getClassFileNameByClassName($className);
        $pattern = sprintf('/%s/', str_replace('/', '\/', $this->config->getVendorDirectory()));

        return (bool)preg_match($pattern, $filename);
    }

    /**
     * @phpstan-param class-string $className
     *
     * @param string $className
     *
     * @throws \RuntimeException
     *
     * @return string
     */
    protected function getClassFileNameByClassName(string $className): string
    {
        $fileName = (new ReflectionClass($className))->getFileName();
        if ($fileName === false) {
            throw new RuntimeException('Cannot parse file name from ' . $className);
        }

        return $fileName;
    }

    /**
     * @phpstan-return array<class-string>
     *
     * @param string $fileContent
     *
     * @return array<string>
     */
    protected function getUsedClassNamesInFile(string $fileContent): array
    {
        $pattern = '/^use (.*);/m';
        $usedClasses = [];
        if (preg_match_all($pattern, $fileContent, $matches, PREG_SET_ORDER)) {
            foreach ($matches as $match) {
                /** @phpstan-var class-string $class */
                $class = explode(' ', $match[1])[0];
                $usedClasses[] = $class;
            }
        }

        return $usedClasses;
    }
}
