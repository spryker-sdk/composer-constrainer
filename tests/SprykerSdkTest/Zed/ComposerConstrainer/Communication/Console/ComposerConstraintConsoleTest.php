<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdkTest\Zed\ComposerConstrainer\Communication\Console;

use Codeception\Test\Unit;
use SprykerSdk\Zed\ComposerConstrainer\Communication\Console\ComposerConstraintConsole;
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
        $this->tester->haveComposerJsonAndOverriddenClass('spryker/module', '^1.0.0');

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
    public function testExecuteInDryRunWillOutputErrorCodeAndMessageWhenModuleExtendedButNotConstrainedInComposerJson(): void
    {
        $this->tester->haveOverriddenClass();

        $command = new ComposerConstraintConsole();
        $command->setFacade($this->tester->getFacade());

        $commandTester = $this->tester->getConsoleTester($command);

        $arguments = [
            'command' => $command->getName(),
            '--' . ComposerConstraintConsole::OPTION_DRY_RUN => true,
        ];

        $commandTester->execute($arguments, ['verbosity' => Output::VERBOSITY_VERY_VERBOSE]);

        $this->assertSame(ComposerConstraintConsole::CODE_ERROR, $commandTester->getStatusCode());
        $this->assertRegExp('/Expected to find a constraint for "spryker\/module" in your composer.json, but none found./', $commandTester->getDisplay());
    }

    /**
     * @group single
     * @return void
     */
    public function testExecuteInDryRunWillOutputSuccessCodeWhenNoExtendedModuleFound(): void
    {
        $this->tester->haveComposerJson('spryker/module', '^1.0.0');

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
        $this->tester->haveComposerJsonAndOverriddenClass('spryker/module', '~1.0.0');

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
        $this->tester->haveComposerJsonAndOverriddenClass('spryker/module', '^1.0.0');

        $command = new ComposerConstraintConsole();
        $command->setFacade($this->tester->getFacade());
        $commandTester = $this->tester->getConsoleTester($command);

        $arguments = [
            'command' => $command->getName(),
        ];

        $commandTester->execute($arguments);

        $this->tester->assertComposerRequire('spryker/module', '~1.0.0');
    }

    /**
     * @return void
     */
    public function testExecuteWillUpdateComposerJsonRequireDevWhenModuleExtendedAndConstrainedWithCaret(): void
    {
        $this->tester->haveComposerJsonAndOverriddenClass('spryker/module', '^1.0.0', 'require-dev');

        $command = new ComposerConstraintConsole();
        $command->setFacade($this->tester->getFacade());
        $commandTester = $this->tester->getConsoleTester($command);

        $arguments = [
            'command' => $command->getName(),
        ];

        $commandTester->execute($arguments);

        $this->tester->assertComposerRequireDev('spryker/module', '~1.0.0');
    }

    /**
     * @return void
     */
    public function testExecuteWillNotUpdateComposerJsonWhenNoViolationFound(): void
    {
        $this->tester->haveComposerJson('spryker/module', '^1.0.0');

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
        $this->tester->haveComposerJsonAndOverriddenClass('spryker/module', '^1.0.0');

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
