<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdkTest\Zed\ComposerConstrainer\Business\Finder\SourceFinder;

use Codeception\Test\Unit;
use SprykerSdk\Zed\ComposerConstrainer\Business\Finder\SourceFinder\SourceFinder;

/**
 * @group SprykerSdk
 * @group Zed
 * @group ComposerConstrainer
 * @group Business
 * @group Finder
 * @group SourceFinder
 * @group SourceFinderTest
 */
class SourceFinderTest extends Unit
{
    /**
     * @var \SprykerSdkTest\Zed\ComposerConstrainer\ComposerConstrainerBusinessTester
     */
    protected $tester;

    /**
     * @return void
     */
    public function testFindShouldFindOverriddenModule(): void
    {
        // Arrange
        $root = $this->tester->getVirtualDirectoryWhereModuleClassIsOverridden();

        $composerConstrainerConfig = $this->tester->mockConfigMethod('getProjectRootPath', function () use ($root) {
            return $root;
        });

        $sourceFinder = new SourceFinder($composerConstrainerConfig);

        // Act
        $usedModuleTransferCollection = $sourceFinder->find();

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

        $sourceFinder = new SourceFinder($composerConstrainerConfig);

        // Act
        $usedModuleTransferCollection = $sourceFinder->find();

        // Assert
        $this->assertCount(0, $usedModuleTransferCollection->getUsedModules());
    }

    /**
     * @return void
     */
    public function testFindShouldReturnEmptyUsedModuleTransferCollectionWhenOrmOrGeneratedDependencyWasFound(): void
    {
        // Arrange
        $root = $this->tester->getVirtualDirectoryWithOrmAndGeneratedDependencies();

        $composerConstrainerConfig = $this->tester->mockConfigMethod('getProjectRootPath', function () use ($root) {
            return $root;
        });

        $sourceFinder = new SourceFinder($composerConstrainerConfig);

        // Act
        $usedModuleTransferCollection = $sourceFinder->find();

        // Assert
        $this->assertCount(0, $usedModuleTransferCollection->getUsedModules());
    }

    /**
     * @return void
     */
    public function testFindShouldNotFindTheModuleConfig(): void
    {
        // Arrange
        $root = $this->tester->getVirtualDirectoryWhereModuleConfigIsOverridden();

        $composerConstrainerConfig = $this->tester->mockConfigMethod('getProjectRootPath', function () use ($root) {
            return $root;
        });

        $sourceFinder = new SourceFinder($composerConstrainerConfig);

        // Act
        $usedModuleTransferCollection = $sourceFinder->find();

        // Assert
        $this->assertCount(0, $usedModuleTransferCollection->getUsedModules());
    }

    /**
     * @return void
     */
    public function testFindShouldNotFindTheModuleDependencyProvider(): void
    {
        // Arrange
        $root = $this->tester->getVirtualDirectoryWhereModuleDependencyProviderIsOverridden();

        $composerConstrainerConfig = $this->tester->mockConfigMethod('getProjectRootPath', function () use ($root) {
            return $root;
        });

        $sourceFinder = new SourceFinder($composerConstrainerConfig);

        // Act
        $useModuleTransferCollection = $sourceFinder->find();

        // Assert
        $this->assertCount(0, $useModuleTransferCollection->getUsedModules());
    }
}
