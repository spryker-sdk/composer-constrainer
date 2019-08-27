<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdkTest\Zed\ComposerConstrainer\Business\Finder\ExtendedModuleFinder;

use Codeception\Test\Unit;
use SprykerSdk\Zed\ComposerConstrainer\Business\Finder\ExtendedModuleFinder\ExtendedModuleFinder;

/**
 * @group SprykerSdk
 * @group Zed
 * @group ComposerConstrainer
 * @group Business
 * @group Finder
 * @group ExtendedModuleFinder
 * @group ExtendedModuleFinderTest
 */
class ExtendedModuleFinderTest extends Unit
{
    /**
     * @var \SprykerSdkTest\Zed\ComposerConstrainer\ComposerConstrainerBusinessTester
     */
    protected $tester;

    /**
     * @return void
     */
    public function testFindShouldFindExtendedModule(): void
    {
        // Arrange
        $root = $this->tester->getVirtualDirectoryWhereModuleClassIsExtended();

        $composerConstrainerConfig = $this->tester->mockConfigMethod('getProjectRootPath', function () use ($root) {
            return $root;
        });

        $ExtendedModuleFinder = new ExtendedModuleFinder($composerConstrainerConfig);

        // Act
        $usedModuleTransferCollection = $ExtendedModuleFinder->find();

        // Assert
        $this->assertCount(1, $usedModuleTransferCollection->getUsedModules());
    }

    /**
     * @return void
     */
    public function testFindShouldReturnEmptyUsedModuleTransferCollectionWhenDirectoryIsNotValid(): void
    {
        // Arrange
        $composerConstrainerConfig = $this->tester->mockConfigMethod('getProjectRootPath', function () {
            return '$root/not/existing/directory';
        });

        $ExtendedModuleFinder = new ExtendedModuleFinder($composerConstrainerConfig);

        // Act
        $usedModuleTransferCollection = $ExtendedModuleFinder->find();

        // Assert
        $this->assertCount(0, $usedModuleTransferCollection->getUsedModules());
    }

    /**
     * @return void
     */
    public function testFindShouldNotFindTheModuleConfig(): void
    {
        // Arrange
        $root = $this->tester->getVirtualDirectoryWhereModuleConfigIsExtended();

        $composerConstrainerConfig = $this->tester->mockConfigMethod('getProjectRootPath', function () use ($root) {
            return $root;
        });

        $ExtendedModuleFinder = new ExtendedModuleFinder($composerConstrainerConfig);

        // Act
        $usedModuleTransferCollection = $ExtendedModuleFinder->find();

        // Assert
        $this->assertCount(0, $usedModuleTransferCollection->getUsedModules());
    }

    /**
     * @return void
     */
    public function testFindShouldNotFindTheModuleDependencyProvider(): void
    {
        // Arrange
        $root = $this->tester->getVirtualDirectoryWhereModuleDependencyProviderIsExtended();

        $composerConstrainerConfig = $this->tester->mockConfigMethod('getProjectRootPath', function () use ($root) {
            return $root;
        });

        $ExtendedModuleFinder = new ExtendedModuleFinder($composerConstrainerConfig);

        // Act
        $useModuleTransferCollection = $ExtendedModuleFinder->find();

        // Assert
        $this->assertCount(0, $useModuleTransferCollection->getUsedModules());
    }
}
