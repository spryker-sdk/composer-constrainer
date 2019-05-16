<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdkTest\Zed\ComposerConstrainer\Communication\Console;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\ModuleTransfer;
use Generated\Shared\Transfer\OrganizationTransfer;
use SprykerSdk\Zed\ComposerConstrainer\Communication\Console\ComposerConstraintConsole;
use SprykerSdk\Zed\ComposerConstrainer\Dependency\Facade\ComposerConstrainerToModuleFinderFacadeBridge;
use SprykerSdk\Zed\ModuleFinder\Business\ModuleFinderFacade;
use Symfony\Component\Console\Output\Output;

/**
 * Auto-generated group annotations
 * @group SprykerSdkTest
 * @group Zed
 * @group ComposerConstrainer
 * @group Communication
 * @group Console
 * @group ConstraintConsoleTest
 * Add your own group annotations below this line
 */
class ComposerConstraintConsoleTest extends Unit
{
    /**
     * @var \SprykerSdkTest\Zed\ComposerConstrainer\ComposerConstrainerCommunicationTester
     */
    protected $tester;

    /**
     * @return void
     */
    public function testExecuteInDryRunWillOutputErrorCodeWhenModuleExtendedAndConstrainedWithCaret(): void
    {
        $this->tester->haveComposerRequire('spryker/module-a', '^1.0.0');
        $this->tester->haveDependencyProvider('Spryker', 'ModuleA', 'Zed');

        $this->tester->mockModuleFinder();
        $this->tester->mockConfigMethod('getProjectRootPath', codecept_data_dir('Fixtures/project/'));

        $command = new ComposerConstraintConsole();
        $command->setFacade($this->tester->getFacade());

        $commandTester = $this->tester->getConsoleTester($command);

        $arguments = [
            'command' => $command->getName(),
            '--' . ComposerConstraintConsole::OPTION_DRY_RUN => true,
        ];

        $commandTester->execute($arguments);

        $this->assertSame(ComposerConstraintConsole::CODE_ERROR, $commandTester->getStatusCode());
    }

    /**
     * @return void
     */
    public function testExecuteInDryRunAndVeryVerboseWillOutputErrorCodeAndMessagesWhenModuleExtendedAndConstrainedWithCaret(): void
    {
        $this->tester->haveComposerRequire('spryker/module-a', '^1.0.0');
        $this->tester->haveComposerRequire('spryker/module-b', '^2.0.0');

        $this->tester->mockModuleFinder();
        $this->tester->haveDependencyProvider('Spryker', 'ModuleA', 'Zed');
        $this->tester->haveConfigFileWithUsedModule('Spryker', 'ModuleB');

        $this->tester->mockConfigMethod('getProjectRootPath', codecept_data_dir('Fixtures/project/'));

        $command = new ComposerConstraintConsole();
        $command->setFacade($this->tester->getFacade());

        $commandTester = $this->tester->getConsoleTester($command);

        $arguments = [
            'command' => $command->getName(),
            '--' . ComposerConstraintConsole::OPTION_DRY_RUN => true,
        ];

        $commandTester->execute($arguments, ['verbosity' => Output::VERBOSITY_VERY_VERBOSE]);

        $this->assertSame(ComposerConstraintConsole::CODE_ERROR, $commandTester->getStatusCode());
        $this->assertRegExp('/Expected version "\~1\.0\.0" but current version is "\^1\.0\.0"/', $commandTester->getDisplay());
        $this->assertRegExp('/Expected version "\~2\.0\.0" but current version is "\^2\.0\.0"/', $commandTester->getDisplay());
    }

    /**
     * @return void
     */
    public function testExecuteInDryRunWillOutputErrorCodeAndMessageWhenModuleExtendedButNotConstrainedInComposerJson(): void
    {
        $this->tester->haveComposerRequire('spryker/module-b', '^1.0.0');
        $this->tester->haveDependencyProvider('Spryker', 'ModuleA', 'Zed');

        $this->tester->mockModuleFinder();
        $this->tester->mockConfigMethod('getProjectRootPath', codecept_data_dir('Fixtures/project/'));

        $command = new ComposerConstraintConsole();
        $command->setFacade($this->tester->getFacade());

        $commandTester = $this->tester->getConsoleTester($command);

        $arguments = [
            'command' => $command->getName(),
            '--' . ComposerConstraintConsole::OPTION_DRY_RUN => true,
        ];

        $commandTester->execute($arguments, ['verbosity' => Output::VERBOSITY_VERY_VERBOSE]);

        $this->assertSame(ComposerConstraintConsole::CODE_ERROR, $commandTester->getStatusCode());
        $this->assertRegExp('/Expected to find a constraint for "spryker\/module-a" in your composer.json, but none found./', $commandTester->getDisplay());
    }

    /**
     * @return void
     */
    public function testExecuteInDryRunWillOutputSuccessCodeWhenNoExtendedModuleFound(): void
    {
        $this->tester->haveComposerRequire('spryker/module-a', '^1.0.0');

        $this->tester->mockModuleFinder();
        $this->tester->mockConfigMethod('getProjectRootPath', codecept_data_dir('Fixtures/project/'));

        $command = new ComposerConstraintConsole();
        $command->setFacade($this->tester->getFacade());

        $commandTester = $this->tester->getConsoleTester($command);

        $arguments = [
            'command' => $command->getName(),
            '--' . ComposerConstraintConsole::OPTION_DRY_RUN => true,
        ];

        $commandTester->execute($arguments, ['verbosity' => Output::VERBOSITY_VERY_VERBOSE]);

        $this->assertSame(ComposerConstraintConsole::CODE_SUCCESS, $commandTester->getStatusCode());
    }

    /**
     * @return void
     */
    public function testExecuteInDryRunWillOutputSuccessCodeWhenModuleExtendedAndConstrainedWithTilde(): void
    {
        $this->tester->haveComposerRequire('spryker/module-a', '~1.0.0');
        $this->tester->haveDependencyProvider('Spryker', 'ModuleA', 'Zed');

        $this->tester->mockModuleFinder();
        $this->tester->mockConfigMethod('getProjectRootPath', codecept_data_dir('Fixtures/project/'));

        $command = new ComposerConstraintConsole();
        $command->setFacade($this->tester->getFacade());
        $commandTester = $this->tester->getConsoleTester($command);

        $arguments = [
            'command' => $command->getName(),
            '--' . ComposerConstraintConsole::OPTION_DRY_RUN => true,
        ];

        $commandTester->execute($arguments);

        $this->assertSame(ComposerConstraintConsole::CODE_SUCCESS, $commandTester->getStatusCode());
    }

    /**
     * @return void
     */
    public function testExecuteWillUpdateComposerJsonRequireWhenModuleExtendedAndConstrainedWithCaret(): void
    {
        $this->tester->haveComposerRequire('spryker/module-a', '^1.0.0');
        $this->tester->haveDependencyProvider('Spryker', 'ModuleA', 'Zed');

        $this->tester->mockModuleFinder();
        $this->tester->mockConfigMethod('getProjectRootPath', codecept_data_dir('Fixtures/project/'));

        $command = new ComposerConstraintConsole();
        $command->setFacade($this->tester->getFacade());
        $commandTester = $this->tester->getConsoleTester($command);

        $arguments = [
            'command' => $command->getName(),
        ];

        $commandTester->execute($arguments);

        $this->tester->assertComposerRequire('spryker/module-a', '~1.0.0');
    }

    /**
     * @return void
     */
    public function testExecuteWillUpdateComposerJsonRequireDevWhenModuleExtendedAndConstrainedWithCaret(): void
    {
        $this->tester->haveComposerRequireDev('spryker/module-a', '^1.0.0');
        $this->tester->haveDependencyProvider('Spryker', 'ModuleA', 'Zed');

        $this->tester->mockModuleFinder();
        $this->tester->mockConfigMethod('getProjectRootPath', codecept_data_dir('Fixtures/project/'));

        $command = new ComposerConstraintConsole();
        $command->setFacade($this->tester->getFacade());
        $commandTester = $this->tester->getConsoleTester($command);

        $arguments = [
            'command' => $command->getName(),
        ];

        $commandTester->execute($arguments);

        $this->tester->assertComposerRequireDev('spryker/module-a', '~1.0.0');
    }

    /**
     * @return void
     */
    public function testExecuteWillNotUpdateComposerJsonWhenNoViolationFound(): void
    {
        $this->tester->haveComposerRequire('spryker/module-a', '^1.0.0');
        $this->tester->mockModuleFinder();
        $this->tester->mockConfigMethod('getProjectRootPath', codecept_data_dir('Fixtures/project/'));

        $command = new ComposerConstraintConsole();
        $command->setFacade($this->tester->getFacade());
        $commandTester = $this->tester->getConsoleTester($command);

        $arguments = [
            'command' => $command->getName(),
        ];

        $commandTester->execute($arguments);

        $this->assertSame(ComposerConstraintConsole::CODE_SUCCESS, $commandTester->getStatusCode());
    }

    /**
     * @return void
     */
    public function testExecuteWillNotUpdateComposerJsonWhenMoreThanOneMatchingModuleFound(): void
    {
        $this->tester->haveComposerRequire('spryker/module-c', '^1.0.0');
        $this->tester->haveDependencyProvider('Spryker', 'ModuleC', 'Zed');

        $this->tester->mockModuleFinder();
        $this->tester->mockConfigMethod('getProjectRootPath', codecept_data_dir('Fixtures/project/'));

        $command = new ComposerConstraintConsole();
        $command->setFacade($this->tester->getFacade());
        $commandTester = $this->tester->getConsoleTester($command);

        $arguments = [
            'command' => $command->getName(),
        ];

        $commandTester->execute($arguments);

        $this->assertSame(ComposerConstraintConsole::CODE_SUCCESS, $commandTester->getStatusCode());
    }
}
