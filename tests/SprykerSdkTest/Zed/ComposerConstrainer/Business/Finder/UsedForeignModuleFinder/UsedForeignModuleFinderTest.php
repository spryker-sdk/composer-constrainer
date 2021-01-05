<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdkTest\Zed\ComposerConstrainer\Business\Finder\UsedForeignModuleFinder;

use Codeception\Test\Unit;
use SprykerSdk\Zed\ComposerConstrainer\Business\Composer\ComposerJson\ComposerJsonReader;
use SprykerSdk\Zed\ComposerConstrainer\Business\Finder\UsedForeignModuleFinder\UsedForeignModuleFinder;

/**
 * @group SprykerSdk
 * @group Zed
 * @group ComposerConstrainer
 * @group Business
 * @group Finder
 * @group ExtendedModuleFinder
 * @group ExtendedModuleFinderTes
 */
class UsedForeignModuleFinderTest extends Unit
{
    /**
     * @var \SprykerSdkTest\Zed\ComposerConstrainer\ComposerConstrainerBusinessTester
     */
    protected $tester;

    /**
     * @return void
     */
    public function testFindReturnsEmptyUsedModuleTransferCollectionWhenDirectoryIsNotValid(): void
    {
        // Arrange
        $usedForeignModuleFinder = $this->getFinder('$root/not/existing/directory');

        // Act
        $usedModuleTransferCollection = $usedForeignModuleFinder->find();

        // Assert
        $this->assertCount(0, $usedModuleTransferCollection->getUsedModules());
    }

    /**
     * @return void
     */
    public function testFindSkipsModuleDependencyProvider(): void
    {
        // Arrange
        $root = $this->tester->getVirtualDirectoryWhereModuleDependencyProviderIsExtended();
        $usedForeignModuleFinder = $this->getFinder($root);

        // Act
        $usedModuleTransferCollection = $usedForeignModuleFinder->find();

        // Assert
        $this->assertCount(0, $usedModuleTransferCollection->getUsedModules());
    }

    /**
     * @return void
     */
    public function testFindSkipsConfigExtended(): void
    {
        $root = $this->tester->getVirtualDirectoryWhereModuleConfigIsExtended();
        $usedForeignModuleFinder = $this->getFinder($root);

        // Act
        $usedModuleTransferCollection = $usedForeignModuleFinder->find();

        // Assert
        $this->assertCount(0, $usedModuleTransferCollection->getUsedModules());
    }

    /**
     * @return void
     */
    public function testFindSkipsCoreModules(): void
    {
        // Arrange
        $root = $this->tester->getVirtualDirectoryWhereModuleClassIsExtended();
        $usedForeignModuleFinder = $this->getFinder($root);

        // Act
        $usedModuleTransferCollection = $usedForeignModuleFinder->find();

        // Assert
        $this->assertCount(0, $usedModuleTransferCollection->getUsedModules());
    }

    /**
     * @return void
     */
    public function testFindFindsUsedForeignModule(): void
    {
        // Arrange
        $root = $this->tester->getVirtualDirectoryWhereForeignModuleIsUsed();
        $usedForeignModuleFinder = $this->getFinder($root);

        // Act
        $usedModuleTransferCollection = $usedForeignModuleFinder->find();

        // Assert
        $this->assertCount(1, $usedModuleTransferCollection->getUsedModules());
    }

    /**
     * @param string $root
     *
     * @return \SprykerSdk\Zed\ComposerConstrainer\Business\Finder\UsedForeignModuleFinder\UsedForeignModuleFinder
     */
    protected function getFinder(string $root): UsedForeignModuleFinder
    {
        $this->tester->mockConfigMethod('getProjectRootPath', function () use ($root) {
            return $root;
        });

        $composerConstrainerConfig = $this->tester->mockConfigMethod('getVendorDirectory', function () use ($root) {
            return $root . 'vendor/';
        });

        $composerJsonReader = new ComposerJsonReader($composerConstrainerConfig);

        return new UsedForeignModuleFinder($composerConstrainerConfig, $composerJsonReader);
    }
}
