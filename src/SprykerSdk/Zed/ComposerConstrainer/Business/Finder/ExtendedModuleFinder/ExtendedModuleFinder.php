<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Zed\ComposerConstrainer\Business\Finder\ExtendedModuleFinder;

use Closure;
use Generated\Shared\Transfer\UsedModuleCollectionTransfer;
use Generated\Shared\Transfer\UsedModuleTransfer;
use PHPStan\BetterReflection\BetterReflection;
use PHPStan\BetterReflection\Reflector\ClassReflector;
use PHPStan\BetterReflection\SourceLocator\Type\SingleFileSourceLocator;
use ReflectionClass;
use SprykerSdk\Zed\ComposerConstrainer\Business\Finder\FinderInterface;
use SprykerSdk\Zed\ComposerConstrainer\ComposerConstrainerConfig;
use Symfony\Component\Finder\Finder;
use Symfony\Component\Finder\SplFileInfo;

class ExtendedModuleFinder implements FinderInterface
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
            $usedModuleCollectionTransfer = $this->addExtendedModules($usedModuleCollectionTransfer, $splFileInfo);
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
    protected function addExtendedModules(UsedModuleCollectionTransfer $usedModuleCollectionTransfer, SplFileInfo $splFileInfo): UsedModuleCollectionTransfer
    {
        $fileContent = $splFileInfo->getContents();
        $coreNamespaces = $this->config->getCoreNamespaces();
        $pattern = sprintf('/(?<organization>%s)\\\\(Client|Glue|Shared|Service|Yves|Zed)\\\\(?<module>\w*)\\\\/', implode('|', $coreNamespaces));

        foreach ($this->getExtendedClassesInFile($splFileInfo) as $extendedClassName) {
            if (preg_match_all($pattern, $extendedClassName, $matches, PREG_SET_ORDER)) {
                foreach ($matches as $match) {
                    $usedModuleTransfer = new UsedModuleTransfer();
                    $usedModuleTransfer
                        ->setOrganization($match['organization'])
                        ->setModule($match['module']);

                    $usedModuleCollectionTransfer->addUsedModule($usedModuleTransfer);
                }
            }
        }

        return $usedModuleCollectionTransfer;
    }

    /**
     * @param \Symfony\Component\Finder\SplFileInfo $splFileInfo
     *
     * @return array<string>
     */
    protected function getExtendedClassesInFile(SplFileInfo $splFileInfo): array
    {
        $astLocator = (new BetterReflection())->astLocator();
        $reflector = new ClassReflector(new SingleFileSourceLocator($splFileInfo->getPathname(), $astLocator));
        $classes = $reflector->getAllClasses();
        $extendedClasses = [];

        foreach ($classes as $class) {
            if ($class->isAnonymous()) {
                continue;
            }
            
            $classNameFragments = explode('\\', $class->getName());
            array_shift($classNameFragments);
            $reflectionClass = new ReflectionClass($class->getName());
            $extended = $reflectionClass->getParentClass();

            if ($extended) {
                $extendedClassNameFragments = explode('\\', $extended->getName());
                array_shift($extendedClassNameFragments);

                if ($classNameFragments === $extendedClassNameFragments) {
                    $extendedClasses[] = $extended->getName();
                }
            }
        }

        return $extendedClasses;
    }
}
