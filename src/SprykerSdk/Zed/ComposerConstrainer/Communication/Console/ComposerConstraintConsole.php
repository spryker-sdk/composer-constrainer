<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Zed\ComposerConstrainer\Communication\Console;

use Generated\Shared\Transfer\ComposerConstraintCollectionTransfer;
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

    /**
     * @return void
     */
    protected function configure(): void
    {
        $this
            ->setName(static::COMMAND_NAME)
            ->setDescription('Updates composer constraints in projects. When a module is extended on project level, this command will change ^ to ~ in the project\'s composer.json. This will make sure that a composer update will only pull patch versions of it for better backwards compatibility.');

        $this->addOption(static::OPTION_DRY_RUN, static::OPTION_DRY_RUN_SHORT, InputOption::VALUE_NONE, 'Use this option to validate your projects\' constraints.');
    }

    /**
     * @param \Symfony\Component\Console\Input\InputInterface $input
     * @param \Symfony\Component\Console\Output\OutputInterface $output
     *
     * @return int
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
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
