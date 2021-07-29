<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Zed\ComposerConstrainer\Communication\Console;

use Generated\Shared\Transfer\ComposerConstraintCollectionTransfer;
use Generated\Shared\Transfer\ComposerConstraintTransfer;
use Spryker\Zed\Kernel\Communication\Console\Console;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * @method \SprykerSdk\Zed\ComposerConstrainer\Business\ComposerConstrainerFacadeInterface getFacade()
 */
class ComposerConstraintConsole extends Console
{
    public const COMMAND_NAME = 'code:constraint:modules';
    public const OPTION_DRY_RUN = 'dry-run';
    public const OPTION_DRY_RUN_SHORT = 'd';
    public const OPTION_VERBOSE_RUN = 'verbose-run';
    public const OPTION_VERBOSE_RUN_SHORT = 'p';
    public const OPTION_ADD_REASONS = 'add-reasons';
    public const OPTION_ADD_REASONS_SHORT = 'r';

    /**
     * @return void
     */
    protected function configure(): void
    {
        $this
            ->setName(static::COMMAND_NAME)
            ->setDescription('Updates composer constraints in projects. When a module is extended on project level, this command will change ^ to ~ in the project\'s composer.json. This will make sure that a composer update will only pull patch versions of it for better backwards compatibility.');

        $this->addOption(static::OPTION_DRY_RUN, static::OPTION_DRY_RUN_SHORT, InputOption::VALUE_NONE, 'Use this option to validate your projects\' constraints.');
        $this->addOption(static::OPTION_VERBOSE_RUN, static::OPTION_VERBOSE_RUN_SHORT, InputOption::VALUE_NONE, 'Use this option to validate your projects\' constraints.');
        $this->addOption(static::OPTION_ADD_REASONS, static::OPTION_ADD_REASONS_SHORT, InputOption::VALUE_NONE, 'Use this option to validate your projects\' constraints.');
    }

    /**
     * @param \Symfony\Component\Console\Input\InputInterface $input
     * @param \Symfony\Component\Console\Output\OutputInterface $output
     *
     * @return int
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        if ($input->getOption(static::OPTION_VERBOSE_RUN)) {
            return $this->runVerboseValidation((bool)$input->getOption(static::OPTION_ADD_REASONS));
        }
        if ($input->getOption(static::OPTION_DRY_RUN)) {
            return $this->runValidation();
        }

        return $this->runUpdate();
    }

    /**
     * @return int
     */
    protected function runValidation(): int
    {
        $composerConstraintCollectionTransfer = $this->getFacade()->validateConstraints();

        if ($composerConstraintCollectionTransfer->getComposerConstraints()->count() === 0) {
            $this->output->writeln('<fg=green>No constraint issues found.</>');

            return static::CODE_SUCCESS;
        }

        $this->outputValidationFindings($composerConstraintCollectionTransfer);

        $this->output->writeln(sprintf('<fg=magenta>%s fixable constraint issues found.</>', $composerConstraintCollectionTransfer->getComposerConstraints()->count()));

        return static::CODE_ERROR;
    }

    /**
     * @return int
     */
    protected function runVerboseValidation(bool $addReasons): int
    {
        $composerConstraintCollectionTransfer = $this->getFacade()->validateConstraints(true);

        if ($composerConstraintCollectionTransfer->getComposerConstraints()->count() === 0) {
            $this->output->writeln('<fg=green>No constraint issues found.</>');

            return static::CODE_SUCCESS;
        }

        $this->outputVerboseValidationFindings($composerConstraintCollectionTransfer, $addReasons);

        $this->output->writeln(sprintf('<fg=magenta>%s constraint issues found.</>', $composerConstraintCollectionTransfer->getComposerConstraints()->count()));

        return static::CODE_ERROR;
    }

    /**
     * @param \Generated\Shared\Transfer\ComposerConstraintCollectionTransfer $composerConstraintCollectionTransfer
     *
     * @return void
     */
    protected function outputVerboseValidationFindings(ComposerConstraintCollectionTransfer $composerConstraintCollectionTransfer, bool $addReasons): void
    {
        $this->output->writeln(
            sprintf(
                '%-70s | %10s %10s | %10s | %8s | %8s | %10s | %10s | Reasons',
                'Module',
                'Customised',
                'Configured',
                'Line count',
                'Expected',
                'Json',
                'Json',
                'Locked'
            )
        );
        foreach ($composerConstraintCollectionTransfer->getComposerConstraints() as $composerConstraintTransfer) {
            $this->output->writeln(
                sprintf(
                    '%-70s | %10s %10s | %10s | %8s | %8s | %10s | %10s | %s',
                    $composerConstraintTransfer->getName(),
                    $composerConstraintTransfer->getModuleInfo()->getIsCustomised() ? 'Yes' : '',
                    $composerConstraintTransfer->getModuleInfo()->getIsConfigured() ? 'Yes' : '',
                    $composerConstraintTransfer->getModuleInfo()->getCustomisedLogicLineCount() ?: 0,
                    $composerConstraintTransfer->getModuleInfo()->getExpectedConstraintLock(),
                    $composerConstraintTransfer->getModuleInfo()->getJsonConstraintLock(),
                    $composerConstraintTransfer->getModuleInfo()->getJsonVersion(),
                    $composerConstraintTransfer->getModuleInfo()->getLockedVersion(),
                    $addReasons ? '{"reasons:": ["' . implode('","', array_unique($composerConstraintTransfer->getModuleInfo()->getConstraintReasons())) . '"]}' : ''
                )
            );
        }
    }

    /**
     * @param \Generated\Shared\Transfer\ComposerConstraintCollectionTransfer $composerConstraintCollectionTransfer
     *
     * @return void
     */
    protected function outputValidationFindings(ComposerConstraintCollectionTransfer $composerConstraintCollectionTransfer): void
    {
        foreach ($composerConstraintCollectionTransfer->getComposerConstraints() as $composerConstraintTransfer) {
            $this->output->writeln(sprintf('<fg=yellow>%s</> appears to be extended on project level.', $composerConstraintTransfer->getName()));
            if (!$this->output->isVerbose()) {
                continue;
            }
            foreach ($composerConstraintTransfer->getMessages() as $messageTransfer) {
                $this->output->writeln('- ' . $messageTransfer->getMessage());
            }
        }
    }

    /**
     * @return int
     */
    protected function runUpdate(): int
    {
        $composerConstraintCollectionTransfer = $this->getFacade()->updateConstraints();

        if ($composerConstraintCollectionTransfer->getComposerConstraints()->count() === 0) {
            $this->output->writeln('<fg=green>No constraint issues found.</>');
        } else {
            $this->output->writeln(sprintf('<fg=green>%s constraint issues found and fixed.</>', $composerConstraintCollectionTransfer->getComposerConstraints()->count()));
        }

        return static::CODE_SUCCESS;
    }
}
